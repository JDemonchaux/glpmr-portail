<?php

namespace Glpmr\AuthentificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Glpmr\AuthentificationBundle\Entity\AuthentificationLDAP;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class AuthentificationController extends Controller {

    // Route par défaut, on charge la vue de login
    public function indexAction() {
        $session = new Session();
        if ($session->get("username")) {
            $url = $this->generateUrl("glpmr_peripherique_gestion");
            return $this->redirect($url);
        } else {
            return $this->render('GlpmrAuthentificationBundle:Default:login.html.twig');
        }
    }

    // Route de validation du login
    public function loginAction(Request $request) {
        // Récupération du login et mot de passe

        $login = $request->request->get("username");
        $pass = $request->request->get("password");

        $isConnected = AuthentificationLDAP::open($login, $pass);

        if ($isConnected) {
            AuthentificationLDAP::close();
            $session = new Session();
            // Rentre le nom d'utilisateur en session
            $session->set('username', $login);
            // Ainsi qu'un boolean pour savoir si l'utilisateur est admin
            $isAdmin = AuthentificationLDAP::isAdmin($login, $pass);

            if ($isAdmin) {
                $session->set("admin", true);
            } else {
                $session->set("admin", false);
            }
        }
        else {
            throw new Exception("Erreur connexion, verifier vos identifiants");
        }

        $url = $this->generateUrl("glpmr_peripherique_gestion");
        return $this->redirect($url);
    }

    // Route de déconnexion
    public function logoutAction() {
        $url = $this->generateUrl("glpmr_authentification_homepage");
        return $this->redirect($url);
    }

    // Route d'affichage du compte
//    public function accountAction() {
//        return $this->render('GlpmrAuthentificationBundle:Default:manage_mac_addr.html.twig');
//    }
//
//    public function rechercheAction() {
//        return $this->render('GlpmrAuthentificationBundle:Default:recherche.html.twig');
//    }
}
