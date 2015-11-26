<?php

namespace Glpmr\VirtualMachineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Os
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Os
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
     * @ORM\Column(name="libelleProxMox", type="string", length=255)
     */
    private $libelleProxMox;

    /**
     * @var string
     *
     * @ORM\Column(name="libelleUser", type="string", length=255)
     */
    private $libelleUser;


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
     * Set libelleProxMox
     *
     * @param string $libelleProxMox
     * @return Os
     */
    public function setLibelleProxMox($libelleProxMox)
    {
        $this->libelleProxMox = $libelleProxMox;
    
        return $this;
    }

    /**
     * Get libelleProxMox
     *
     * @return string 
     */
    public function getLibelleProxMox()
    {
        return $this->libelleProxMox;
    }

    /**
     * Set libelleUser
     *
     * @param string $libelleUser
     * @return Os
     */
    public function setLibelleUser($libelleUser)
    {
        $this->libelleUser = $libelleUser;
    
        return $this;
    }

    /**
     * Get libelleUser
     *
     * @return string 
     */
    public function getLibelleUser()
    {
        return $this->libelleUser;
    }
    
    public function __toString()
    {
        return (string) $this->getLibelleUser();
    }
}
