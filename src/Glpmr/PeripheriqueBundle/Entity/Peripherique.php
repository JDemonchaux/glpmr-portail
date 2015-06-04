<?php

namespace Glpmr\PeripheriqueBundle\Entity;

/**
 * Description of AuthentificationLDAP
 *
 * @author Jérôme
 */
Class Peripherique {

    private $id;
    private $hostname;
    private $description;
    private $type;
    private $add_mac;
    private $add_ip;
    private $proprietaire;
    private $proprietaire_classe;

    public function __construct() {
        //TODO
        //Remplir avec les arguments qu'on aura besoin
    }
    
    
    /**
     * Fonction qui permet depuis la promotion d'un étudiant de calculer automatiquement l'adresse IP d'un périphérique.
     * 
     * @return void : set l'attribut add_ip du présent objet.
     */
    public function calculerIP() {
        
    }
    
    
    
    // GETTERS AND SETTERS
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getHostname() {
        return $this->hostname;
    }

    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
    }

    function getAdd_mac() {
        return $this->add_mac;
    }

    function setAdd_mac($add_mac) {
        $this->add_mac = $add_mac;
    }

    function getAdd_ip() {
        return $this->add_ip;
    }

    function setAdd_ip($add_ip) {
        $this->add_ip = $add_ip;
    }

    function getProprietaire() {
        return $this->proprietaire;
    }

    function setProprietaire($proprietaire) {
        $this->proprietaire = $proprietaire;
    }

    function getProprietaire_classe() {
        return $this->proprietaire_classe;
    }

    function setProprietaire_classe($proprietaire_classe) {
        $this->proprietaire_classe = $proprietaire_classe;
    }

}
