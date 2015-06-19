<?php

namespace Glpmr\PeripheriqueBundle\Controller;

use Glpmr\PeripheriqueBundle\Entity\Peripherique;
use Glpmr\PeripheriqueBundle\Entity\PeripheriqueDAO;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class PeripheriqueController extends Controller
{
    public function listerAction() {
        return $this->render("GlpmrPeripheriqueBundle:Default:manage_mac_addr.html.twig");
    }

    public function ajouterAction(Request $request)
    {
        try {
            $obj = new Peripherique();
            $obj->setHostname($request->get("hostname"));
            $obj->setAdd_ip($request->get("ip"));
            $obj->setAdd_mac($request->get("adresse"));
            $obj->setType($request->get("type"));
            $obj->setDescription($request->get("description"));

            $session = new Session();
            $obj->setProprietaire($session->get("username"));
            $dao = new PeripheriqueDAO();
            $dao->ajouter($obj);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function gestionAction() {
        
    }
}
