<?php

namespace Glpmr\AuthentificationBundle\Entity;

use Glpmr\VirtualMachineBundle\Entity\User as User;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of AuthentificationLDAP
 *
 * @author Jérôme
 */
class AuthentificationLDAP
{

    private static $baseDN = "dc=reseau-labo,dc=fr";
//    private static $ldapServer = "PROD-DC-01";
    private static $ldapServer = "172.16.0.100";
    private static $ldapServerPort = 389;
    private static $dn = "dc=reseau-labo,dc=fr";

//    private static $baseDN = "dc=maison,dc=local";
//    private static $dn = "dc=maison,dc=local";
//    private static $ldapServer = "192.168.0.15";
    public static $user_filter = "(objectCategory=user)";
    public static $connexion;


    /**
     * Fonction qui ouvre la connexion AD et valide le login.
     * @param type $login : le login de l'utilisateur.
     * @param type $pass : le mot de passe de l'utilisateur.
     * @return boolean $ldapbind : true si connection OK et false sinon.
     */
    public static function open($login, $pass)
    {

//        ldap_set_option(self::$connexion, LDAP_OPT_PROTOCOL_VERSION, 3);
//         Initialisation de la connexion
        self::$connexion = @ldap_connect(self::$ldapServer, self::$ldapServerPort);
        if (self::$connexion) {
            ldap_set_option(self::$connexion, LDAP_OPT_REFERRALS, 0);
            ldap_set_option(self::$connexion, LDAP_OPT_PROTOCOL_VERSION, 3);

            // Connexion au serveur DAP avec authentification
            $ldapbind = @ldap_bind(self::$connexion, $login, $pass);
        } else {
            $ldapbind = FALSE;
        }
        return $ldapbind;
    }

    /**
     * Fonction qui ferme la liaison Active Directory
     */
    public static function close()
    {
        ldap_close(self::$connexion);
    }


    /**
     * Fonction qui permet de connaitre la classe d'un étudiant
     * @param $username : le nom d'utilisateur AD d'un étudiant
     * @return $classe : la classe de l'étudiant
     */
    public static function getInfosUser($login, $password)
    {
        $user = new User();

        $listeAgent = array();
        $isConnected = AuthentificationLDAP::open($login, $password);
        if ($isConnected) {
            $resultat = ldap_search(self::$connexion, self::$dn, self::$user_filter);
            if (FALSE !== $resultat) {
                $entries = ldap_get_entries(self::$connexion, $resultat);
                foreach ($entries as $unAgent) {
                    // On enlève les user qui servent à rien
                    if (strpos($unAgent['dn'], "OU=Autres")) {

                    } else {
                        array_push($listeAgent, $unAgent['samaccountname'][0]);

                        $user->setPrenom($unAgent[1]["dn"][0]);
                        $user->setNom($unAgent["cn"][0]);
                        //$user->setMail($unAgent["mail"][0]);
                        //$user->setlstGroup($unAgent["memberof"][0]);
                    }
                }
                if ($listeAgent[0] == NULL) {
                    array_shift($listeAgent);
                }
                sort($listeAgent);
            }
        } else {
            // LEVER ERREUR ICI
        }

        return $user;
    }


    /**
     * Fonction qui permet de savoir si l'utilisateur connecté est administrateur
     * @param username : le nom de l'utilisateur courant
     * @param password : obligatoire pour pouvoir faire une recherche dans l'AD
     * @return $admin : boolean TRUE si admin, FALSE sinon
     */

    public static function isAdmin($login, $password)
    {
        self::open($login, $password);

        $isAdmin = FALSE;

        // Search AD
        $results = ldap_search(self::$connexion, self::$dn, "(samaccountname=$login)", array("memberof", "primarygroupid"));
        $entries = ldap_get_entries(self::$connexion, $results);

        var_dump($entries);

        if (isset($entries[0]['memberof'][0])) {
            if (TRUE == strpos($entries[0]['memberof'][0], "Admins du domaine")) {
                $isAdmin = TRUE;
//        } else if (TRUE == strpos($entries[0]['memberof'][1], "Admins du domaine")) {
//            $isAdmin = TRUE;
            }
        }

        return $isAdmin;
    }


    /**
     * Fonction qui renvoie la promotion de l'elève
     * @seealso SAD : schéma de l'AD pour les OU promotions
     */
    public static function getPromotion($login, $password)
    {
        $promotion = null;

        $isConnected = AuthentificationLDAP::open($login, $password);
        if ($isConnected) {
            $filter = "(sAMAccountName=" . $login . ")";
            $resultat = ldap_search(self::$connexion, self::$dn, $filter);
            if (FALSE !== $resultat) {
                $user = ldap_get_entries(self::$connexion, $resultat);
                $user = $user[0];

                $split = explode(",", $user["distinguishedname"][0]);
                // Ici pour choppé le mail
                //$user['mail'][0]
                //TODO : chopper l'OU des profs, et repasser en REGEX


                foreach ($split as $boutDeChaine) {
                    if (strpos($boutDeChaine, "SIOTP ")) {
                        $promotion = $boutDeChaine;
                    } else if (strpos($boutDeChaine, "SIOALT ")) {
                        $promotion = $boutDeChaine;
                    } else if (strpos($boutDeChaine, "ASI ")) {
                        $promotion = $boutDeChaine;
                    }
                }
                // Affiche le promotion du style : ASI 2016
                $promo = str_replace("OU=", "", $promotion);

                if (!$promo == '') {
                    // On transforme ça en ASI 1 ou 2 suivant l'année.
                    $promo = explode(" ", $promo);
                    $annee = self::calculPromotion($promo[1]);
                    $promo_etudiant = $promo[0] . " " . $annee;
                } else {
                    $promo_etudiant = "professeur";
                }
            }
        } else {
            // LEVER ERREUR ICI
        }
        return $promo_etudiant;
    }

    /**
     * Fonction qui calcul la session de l'eleve suivant l'année de sa promo
     * @param $annee : l'annéee de la promo de l'eleve
     */
    public static function calculPromotion($annee)
    {
        $date = new \DateTime();
        $cyear = $date->format("Y");
        $cyear = intval($cyear);
        $month = $date->format("m");
        $session = "";

        if ($month >= 8) {
            $cyear += 0.5;
        }

        if ($annee - $cyear <= 0.5) {
            $session = 2;
        } else {
            $session = 1;
        }

        return $session;
    }

    public static function getListeProf($login, $password)
    {
        $dn = "ou=Professeurs,ou=utilisateurs,dc=labo,dc=lpmr,dc=info";

        $lstProf = array();

        self::open($login, $password);

        // Search AD
        $results = ldap_search(self::$connexion, $dn, '(&(objectClass=user))');
        $entries = ldap_get_entries(self::$connexion, $results);
        foreach ($entries as $res) {
            //var_dump($res);
            if (!(isset($res['mail'][0]) && isset($res['sn'][0]) && isset($res['givenname'][0]))) {

            } else {
                $prof = new User();
                $prof->setMail($res['mail'][0]);
                $prof->setNom($res['sn'][0]);
                $prof->setPrenom($res['givenname'][0]);
                //var_dump($prof);
                array_push($lstProf, $prof);
            }
        }

        return $lstProf;
    }

    public static function getUserCourant($login, $password, $isEleve)
    {
        $dn = "dc=labo,dc=lpmr,dc=info";

        $user = new User();

        self::open($login, $password, $isEleve);

        //Si c'est une prof qui s'est connecté on change le filtre
        if (!$isEleve) {
            $dn = "ou=Professeurs,ou=utilisateurs,dc=labo,dc=lpmr,dc=info";
            //var_dump('On est un prof');
        } else {
            //var_dump('On est un eleve');
        }

        $results = ldap_search(self::$connexion, $dn, self::$user_filter);
        $entries = ldap_get_entries(self::$connexion, $results);
        //var_dump($entries);
        foreach ($entries as $res) {
            if (!(isset($res['mail'][0]) && isset($res['sn'][0])
                    && isset($res['givenname'][0]) && isset($res['samaccountname'][0]))
                || $res['samaccountname'][0] != $login
            ) {
                //var_dump($res);
            } else {
                $user->setMail($res['mail'][0]);
                $user->setNom($res['sn'][0]);
                $user->setPrenom($res['givenname'][0]);
                //var_dump($user);
            }
            //var_dump($res);
        }

        return $user;
    }

}
