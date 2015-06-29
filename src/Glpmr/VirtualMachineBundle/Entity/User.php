<?php

namespace Glpmr\VirtualMachineBundle\Entity;

Class User {

    private $nom;
    private $prenom;
    private $idAD;
    private $mail;
    private $lstGroup;
    
    public function __construct() {
        
    }
    
    public function setNom($nom)
    {
        $this->nom = $nom;
    }
    
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }
    public function setMail($mail)
    {
        $this->mail = $mail;
    }
    public function setlstGroup($lstGroup)
    {
        $this->lstGroup = $lstGroup;
    }
}