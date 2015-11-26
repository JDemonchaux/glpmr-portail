<?php

namespace Glpmr\PeripheriqueBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of AuthentificationLDAP
 *
 * @author Jérôme
 */
Class Peripherique extends Controller
{

    private $id;
    private $hostname;
    private $description;
    private $type;
    private $add_mac;
    private $add_ip;
    private $octet1;
    private $octet2;
    private $octet3;
    private $octet4;
    private $proprietaire;
    private $proprietaire_classe;

    public function __construct()
    {
        //TODO
        //Remplir avec les arguments qu'on aura besoin
    }


    public function ConstruireIP($promotion, $ip, $conn)
    {
        $o1 = 10;
        $o2 = $this->calculOctet2($promotion); // numéro de Classe. AuthentificationLDAP::getPromotion.
        $o3 = $this->calculOctet3($conn); // numero Etudiant. Recup en base le last octet dispo pour la classe. Si l'utilisateur a déjà a numero, on le recup
        $o4 = $ip; // ip choisie lors de l'ajout
        $ip_full = $o1 . "." . $o2 . "." . $o3 . "." . $o4;

        // On en profite ici pour ajouter les octets en attributs du peripherique
        $this->setOctet1($o1);
        $this->setOctet2($o2);
        $this->setOctet3($o3);
        $this->setOctet4($o4);

        return $ip_full;
    }

    /**
     * Calcul du deuxième octet de l'IP
     * 10.xxx
     * @param $promotion : la promotion de l'elève
     */
    public function calculOctet2($promotion)
    {
        $octet = "";
        switch ($promotion) {
            case "SIOTP 1":
                $octet = 1;
                break;
            case "SIOTP 2":
                $octet = 2;
                break;
            case "SIOALT 1":
                $octet = 3;
                break;
            case "SIOALT 2":
                $octet = 4;
                break;
            case "ASI 1":
                $octet = 5;
                break;
            case "ASI 2":
                $octet = 6;
                break;
            default:
                // cas où c'est un prof
                $octet = 0;
        }

        return $octet;
    }

    /**
     * Calcul du troisieme octet de l'IP : le numéro étudiant
     * C'est un numéro incrémental disponible pour chaque étudiant.
     * On va lire en base si l'utilisateur a déjà un troisième octet d'attribué, sinon pour sa classe on prend le dernier 3eme octet enregistré
     * Qu'on incrément de 1
     * @
     */
    public function calculOctet3($conn)
    {
        $dao = new PeripheriqueDAO($conn);
        return $dao->getTroisiemeOctet();
    }


    // GETTERS AND SETTERS
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getHostname()
    {
        return $this->hostname;
    }

    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    function getType()
    {
        return $this->type;
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function getAdd_mac()
    {
        return $this->add_mac;
    }

    function setAdd_mac($add_mac)
    {
        $this->add_mac = $add_mac;
    }

    function getAdd_ip()
    {
        return $this->add_ip;
    }

    function setAdd_ip($add_ip)
    {
        $this->add_ip = $add_ip;
    }

    function getProprietaire()
    {
        return $this->proprietaire;
    }

    function setProprietaire($proprietaire)
    {
        $this->proprietaire = $proprietaire;
    }

    function getProprietaire_classe()
    {
        return $this->proprietaire_classe;
    }

    function setProprietaire_classe($proprietaire_classe)
    {
        $this->proprietaire_classe = $proprietaire_classe;
    }

    /**
     * @return mixed
     */
    public function getOctet2()
    {
        return $this->octet2;
    }

    /**
     * @param mixed $octet2
     */
    public function setOctet2($octet2)
    {
        $this->octet2 = $octet2;
    }

    /**
     * @return mixed
     */
    public function getOctet1()
    {
        return $this->octet1;
    }

    /**
     * @param mixed $octet1
     */
    public function setOctet1($octet1)
    {
        $this->octet1 = $octet1;
    }

    /**
     * @return mixed
     */
    public function getOctet3()
    {
        return $this->octet3;
    }

    /**
     * @param mixed $octet3
     */
    public function setOctet3($octet3)
    {
        $this->octet3 = $octet3;
    }

    /**
     * @return mixed
     */
    public function getOctet4()
    {
        return $this->octet4;
    }

    /**
     * @param mixed $octet4
     */
    public function setOctet4($octet4)
    {
        $this->octet4 = $octet4;
    }


}
