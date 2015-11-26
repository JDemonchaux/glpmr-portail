<?php

namespace Glpmr\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
    * @Security("has_role('ROLE_ELEVE') and has_role('ROLE_ADMIN')")
    *   Route d'affichage du formulaire de recherche
    *   Affiche un formulaire de recherche (par IP/MAC/nom d'étudiant/hostname)
    */
    public function rechercheAction() {
        return $this->render('GlpmrAuthentificationBundle:Default:recherche.html.twig');
    }
        
    /**
    *   Route d'affichage du résultat de la recherche
    *   Résultat sous forme de tableau
    */
    public function resultatAction() {
        
    }
}
