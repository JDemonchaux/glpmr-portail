<?php

namespace Glpmr\VirtualMachineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Demande
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Demande {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomVm", type="string", length=255)
     */
    private $nomVM;

    /**
     * @var string
     *
     * @ORM\Column(name="nomEleve", type="string", length=255)
     */
    private $nomEleve;
    
    /**
     * @var string
     *
     * @ORM\Column(name="prenomEleve", type="string", length=255)
     */
    private $prenomEleve;
    
    /**
     * @var object
     *
     * @ORM\Column(name="prof", type="object")
     */
    private $prof;

    /**
     * @var string
     *
     * @ORM\Column(name="OS", type="string", length=255)
     */
    private $OS;

    /**
     * @var string
     *
     * @ORM\Column(name="adrsReseau", type="string", length=255)
     */
    private $adrsReseau;

    /**
     * @var string
     *
     * @ORM\Column(name="passwordRoot", type="string", length=255)
     */
    private $passwordRoot;

    /**
     * @var string
     *
     * @ORM\Column(name="mailEleve", type="string", length=255)
     */
    private $mailEleve;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set adrsReseau
     *
     * @param string $adrsReseau
     * @return Demande
     */
    public function setAdrsReseau($adrsReseau) {
        $this->adrsReseau = $adrsReseau;

        return $this;
    }

    /**
     * Get adrsReseau
     *
     * @return string 
     */
    public function getAdrsReseau() {
        return $this->adrsReseau;
    }

    /**
     * Set nomVM
     *
     * @param string $nomVM
     * @return Demande
     */
    public function setNomVM($nomVM) {
        $this->nomVM = $nomVM;

        return $this;
    }

    /**
     * Get nomVM
     *
     * @return string 
     */
    public function getNomVM() {
        return $this->nomVM;
    }

    /**
     * Set OS
     *
     * @param string $oS
     * @return Demande
     */
    public function setOS($oS) {
        $this->OS = $oS;

        return $this;
    }

    /**
     * Get OS
     *
     * @return string 
     */
    public function getOS() {
        return $this->OS;
    }

    /**
     * Set passwordRoot
     *
     * @param string $passwordRoot
     * @return Demande
     */
    public function setPasswordRoot($passwordRoot) {
        $this->passwordRoot = $passwordRoot;

        return $this;
    }

    /**
     * Get passwordRoot
     *
     * @return string 
     */
    public function getPasswordRoot() {
        return $this->passwordRoot;
    }

    /**
     * Set prof
     *
     * @param \stdClass $prof
     * @return Demande
     */
    public function setProf($prof)
    {
        $this->prof = $prof;
    
        return $this;
    }

    /**
     * Get prof
     *
     * @return \stdClass 
     */
    public function getProf()
    {
        return $this->prof;
    }

    /**
     * Set mailEleve
     *
     * @param string $mailEleve
     * @return Demande
     */
    public function setMailEleve($mailEleve)
    {
        $this->mailEleve = $mailEleve;
    
        return $this;
    }

    /**
     * Get mailEleve
     *
     * @return string 
     */
    public function getMailEleve()
    {
        return $this->mailEleve;
    }
    
    /**
     * Set nomEleve
     *
     * @param string $nomEleve
     * @return Demande
     */
    public function setNomEleve($nomEleve)
    {
        $this->nomEleve = $nomEleve;
    
        return $this;
    }

    /**
     * Get nomEleve
     *
     * @return string 
     */
    public function getNomEleve()
    {
        return $this->nomEleve;
    }
    /**
     * Set prenomEleve
     *
     * @param string $prenomEleve
     * @return Demande
     */
    public function setPrenomEleve($prenomEleve)
    {
        $this->prenomEleve = $prenomEleve;
    
        return $this;
    }

    /**
     * Get prenomEleve
     *
     * @return string 
     */
    public function getPrenomEleve()
    {
        return $this->prenomEleve;
    }
    
    public function getIdentitee()
    {
        return $this->getPrenomEleve() . " " . $this->getNomEleve();
    }
}
