<?php

namespace Glpmr\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
    * Route par défaut du bundle admin
    * Affiche une liste paginée de tous les periphériques.
    */
    public function listerAllAction() {
        return $this->render('GlpmrAdminBundle::index.html.twig');
    }
    
    /**
    *   Route de suppression d'un groupe de périphérique
    *   Affiche un formulaire pour choisir la promotion à supprimer.
    */
    public function supprimerGroupeAction() {
        
    }
    
    /**
    *   Route de validation de suppression d'un groupe de périphérique
    *   Supprimer tous les périphériques d'une classe donnée
    */
    public function supprimerGroupeValiderAction() {
        
    }
    
    /**
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
