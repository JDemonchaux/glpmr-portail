<?php

namespace Glpmr\PeripheriqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PeripheriqueController extends Controller
{
    public function listerAction() {
        return $this->render("GlpmrPeripheriqueBundle:Default:manage_mac_addr.html.twig");
    }
    
    public function gestionAction() {
        
    }
}
