<?php

namespace Glpmr\AuthentificationBundle\Controller;

use Glpmr\PeripheriqueBundle\Entity\PeripheriqueDAO;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Glpmr\AuthentificationBundle\Entity\AuthentificationLDAP;
use Glpmr\AuthentificationBundle\Entity\CustomError;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class AuthentificationController extends Controller
{

    // Route par défaut, on charge la vue de login
    public function indexAction()
    {
        $session = new Session();
        if ($session->get("username")) {
            $url = $this->generateUrl("glpmr_peripherique_gestion");
            return $this->redirect($url);
        } else {
            return $this->render('GlpmrAuthentificationBundle:Default:login.html.twig');
        }
    }

    // Route de validation du login
    public function loginAction(Request $request)
    {
        // Récupération du login et mot de passe

        $login = $request->request->get("username");
        $pass = $request->request->get("password");

        if (!empty($login) && $login !== "" &&
            !empty($pass) && $pass !== ""
        ) {

//        $isConnected = AuthentificationLDAP::open($login, $pass);
            if (AuthentificationLDAP::open($login, $pass)) {
                AuthentificationLDAP::close();
                $session = new Session();
                // Rentre le nom d'utilisateur en session
                $session->set('username', $login);
                $session->set('password', $pass);

                // On recupère la promotion de l'étudiant qu'on met en session également
                $promotion = AuthentificationLDAP::getPromotion($login, $pass);
                $session->set('promotion', $promotion);

                var_dump($promotion);

                // Ainsi qu'un boolean pour savoir si l'utilisateur est admin
                $isAdmin = AuthentificationLDAP::isAdmin($login, $pass);

                if ($isAdmin) {
                    $session->set("admin", true);
                } else {
                    $session->set("admin", false);
                }

                $url = $this->generateUrl("glpmr_peripherique_gestion");
            } else {
                CustomError::showMessage("Identifiants incorrects");
                $url = $this->generateUrl("glpmr_authentification_homepage");
            }
        } else {
            CustomError::showMessage("Il faut renseigner tous les champs");
            $url = $this->generateUrl("glpmr_authentification_homepage");

        }
        return $this->redirect($url);
    }

    // Route de déconnexion
    public function logoutAction()
    {
        $session = new Session();
        $session->clear();
        $url = $this->generateUrl("glpmr_authentification_homepage");
        return $this->redirect($url);
    }



    // Route d'affichage du compte
//    public function accountAction() {
//        return $this->render('GlpmrAuthentificationBundle:Default:manage_mac_addr.html.twig');
//    }
//

}
