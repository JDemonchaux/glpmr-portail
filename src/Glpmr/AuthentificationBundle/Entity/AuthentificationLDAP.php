<?php

namespace Glpmr\AuthentificationBundle\Entity;

use Glpmr\VirtualMachineBundle\Entity\User as User;

/**
 * Description of AuthentificationLDAP
 *
 * @author Jérôme
 */
class AuthentificationLDAP {

    private static $baseDN = "dc=labo,dc=lpmr,dc=info";
    private static $ldapServer = "PROD-DC-01";
//    private static $ldapServer = "172.16.0.100";
    private static $ldapServerPort = 389;
    private static $dn = 'cn=users,dc=labo,dc=lpmr,dc=info';
    public static $connexion;

    
    /**
     * Fonction qui ouvre la connexion AD et valide le login.
     * @param type $login : le login de l'utilisateur
     * @param type $pass : le mot de passe de l'utilisateur
     * @return boolean $ldapbind : true si connection OK et false sinon
     */
    public static function open($login, $pass) {
        //ldap_set_option(self::$connexion, LDAP_OPT_PROTOCOL_VERSION, 3); 
        
        // Initialisation de la connexion
        self::$connexion = ldap_connect(self::$ldapServer, self::$ldapServerPort);

        if (self::$connexion) {
            // Connexion au serveur LDAP avec authentification
            $ldapbind = ldap_bind(self::$connexion, $login, $pass);
        }
        return $ldapbind;
    }    
    
    /**
     * Fonction qui ferme la liaison Active Directory
     */
    public static function close() {
        ldap_close(self::$connexion);
    }

    
    /**
     * Fonction qui permet de connaitre la classe d'un étudiant
     * @param $username : le nom d'utilisateur AD d'un étudiant
     * @return $classe : la classe de l'étudiant
     */
    public static function getInfosUser($login, $password) {
        
        $user = new User();
        
        $listeAgent = array();
        $isConnected = AuthentificationLDAP::open($login, $password);
        if ($isConnected) {
            $resultat = ldap_search(self::$connexion, self::$dn, self::$user_filter);
            if (FALSE !== $resultat) {
                $entries = ldap_get_entries(self::$connexion, $resultat);
                foreach ($entries as $unAgent) {
                    // On enlève les user dqui servent à rien
                    if (strpos($unAgent['dn'], "OU=Autres")) {
                        
                    } else {
                        array_push($listeAgent, $unAgent['samaccountname'][0]);                       

                        var_dump($unAgent);
                        
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

        // Search AD
        $results = ldap_search(self::$connexion, self::$dn, "(samaccountname=" . $login . ")", array("memberof", "adm_portail"));
        $entries = ldap_get_entries(self::$connexion, $results);
        if ($entries['count'] == 0) {
            return false;
        } else {
            return true;
        }
    }
   
    
    
}
