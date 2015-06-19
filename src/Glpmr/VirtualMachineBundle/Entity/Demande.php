<?php

namespace Glpmr\VirtualMachineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Demande
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Demande
{
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="idEleve", type="integer")
     */
    private $idEleve;

    /**
     * @var string
     *
     * @ORM\Column(name="pool", type="string", length=255)
     */
    private $pool;

    /**
     * @var string
     *
     * @ORM\Column(name="stockage", type="string", length=255)
     */
    private $stockage;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=255)
     */
    private $template;

    /**
     * @var float
     *
     * @ORM\Column(name="ram", type="float")
     */
    private $ram;

    /**
     * @var float
     *
     * @ORM\Column(name="disque", type="float")
     */
    private $disque;

    /**
     * @var float
     *
     * @ORM\Column(name="swap", type="float")
     */
    private $swap;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbrCpu", type="integer")
     */
    private $nbrCpu;

    /**
     * @var string
     *
     * @ORM\Column(name="adrsReseau", type="string", length=255)
     */
    private $adrsReseau;

    /**
     * @var string
     *
     * @ORM\Column(name="professeur", type="string", length=255)
     */
    private $professeur;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Demande
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set idEleve
     *
     * @param integer $idEleve
     * @return Demande
     */
    public function setIdEleve($idEleve)
    {
        $this->idEleve = $idEleve;

        return $this;
    }

    /**
     * Get idEleve
     *
     * @return integer 
     */
    public function getIdEleve()
    {
        return $this->idEleve;
    }

    /**
     * Set pool
     *
     * @param string $pool
     * @return Demande
     */
    public function setPool($pool)
    {
        $this->pool = $pool;

        return $this;
    }

    /**
     * Get pool
     *
     * @return string 
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * Set stockage
     *
     * @param string $stockage
     * @return Demande
     */
    public function setStockage($stockage)
    {
        $this->stockage = $stockage;

        return $this;
    }

    /**
     * Get stockage
     *
     * @return string 
     */
    public function getStockage()
    {
        return $this->stockage;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return Demande
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set ram
     *
     * @param float $ram
     * @return Demande
     */
    public function setRam($ram)
    {
        $this->ram = $ram;

        return $this;
    }

    /**
     * Get ram
     *
     * @return float 
     */
    public function getRam()
    {
        return $this->ram;
    }

    /**
     * Set disque
     *
     * @param float $disque
     * @return Demande
     */
    public function setDisque($disque)
    {
        $this->disque = $disque;

        return $this;
    }

    /**
     * Get disque
     *
     * @return float 
     */
    public function getDisque()
    {
        return $this->disque;
    }

    /**
     * Set swap
     *
     * @param float $swap
     * @return Demande
     */
    public function setSwap($swap)
    {
        $this->swap = $swap;

        return $this;
    }

    /**
     * Get swap
     *
     * @return float 
     */
    public function getSwap()
    {
        return $this->swap;
    }

    /**
     * Set nbrCpu
     *
     * @param integer $nbrCpu
     * @return Demande
     */
    public function setNbrCpu($nbrCpu)
    {
        $this->nbrCpu = $nbrCpu;

        return $this;
    }

    /**
     * Get nbrCpu
     *
     * @return integer 
     */
    public function getNbrCpu()
    {
        return $this->nbrCpu;
    }

    /**
     * Set adrsReseau
     *
     * @param string $adrsReseau
     * @return Demande
     */
    public function setAdrsReseau($adrsReseau)
    {
        $this->adrsReseau = $adrsReseau;

        return $this;
    }

    /**
     * Get adrsReseau
     *
     * @return string 
     */
    public function getAdrsReseau()
    {
        return $this->adrsReseau;
    }

    /**
     * Set professeur
     *
     * @param string $professeur
     * @return Demande
     */
    public function setProfesseur($professeur)
    {
        $this->professeur = $professeur;

        return $this;
    }

    /**
     * Get professeur
     *
     * @return string 
     */
    public function getProfesseur()
    {
        return $this->professeur;
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
    
    // TODO 
    //Retourne la liste des pools
    public static function getListePool()
    {
        return array(
            'Pool 1',
            'Pool 2',
        
        );
    }
    
    // TODO 
    //Retourne la liste des stockage dans un tableau pour le formulaire
    public static function getListeStockage()
    {
        return array(
            'Local',
            'NAS',
        
        );
    }
    
    // TODO 
    //Retourne la liste des stockage dans un tableau pour le formulaire
    public static function getListeTemplate()
    {
        return array(
            'Template 1',
            'Template 2',
        
        );
    }
    
    // TODO 
    //Retourne la liste des RAM dans un tableau pour le formulaire
    public static function getListeRam()
    {
        return array(
            '4 Go',
            '8 Go',
        
        );
    }
    
    // TODO 
    //Retourne la liste des Disques dans un tableau pour le formulaire
    public static function getListeDisque()
    {
        return array(
            'Disque 1',
            'Disque 2',
        
        );
    }
    
    // TODO 
    //Retourne la liste des SWAP dans un tableau pour le formulaire
    public static function getListeSwap()
    {
        return array(
            'Swap 1',
            'Swap 2',
        
        );
    }
    
    // TODO 
    //Retourne la liste des nbrCpu dans un tableau pour le formulaire
    public static function getListeNbrCpu()
    {
        return array(
            '1 Cpu',
            '8 Cpu',
        
        );
    }
    
    // TODO 
    //Retourne la liste des adrsReseau dans un tableau pour le formulaire
    public static function getListeAdrsReseau()
    {
        return array(
            '192.168.1.89',
            '172.125.34.1',
        
        );
    }
    
    // TODO 
    //Retourne la liste des professeurs dans un tableau pour le formulaire
    public static function getListeProfesseur()
    {
        return array(
            'Robert',
            'Michel',
        
        );
    }
}
