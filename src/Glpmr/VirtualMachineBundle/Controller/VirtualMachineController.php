<?php

namespace Glpmr\VirtualMachineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Glpmr\VirtualMachineBundle\Entity\User as User;
use Glpmr\VirtualMachineBundle\Entity\Demande;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Glpmr\AuthentificationBundle\Entity\AuthentificationLDAP;

class VirtualMachineController extends Controller
{    
    public function creationAction(Request $request)
    {           
        $session = new Session();
           // $session->start();
        $login = $session->get('username');
        $password = $session->get('password');        
        
        //On crée le nouvel objet demande
        $demande = new Demande();
        
        $user = new User();
        $user = AuthentificationLDAP::getInfosUser($login, $password);
        
        var_dump($user);
        
        // TODO
        //Ici il faudra récupèrer l'id de l'élève via l'active directory
        $demande->setIdEleve(9999);

        //A SUPPRIMER : l'élève choisira le nom de la VM        
        $demande->setNom("skrface");        
        //A SUPPRIMER : l'élève choisira
        $demande->setDisque(12);
        //A SUPPRIMER : l'élève choisira
        $demande->setPool("lh");        
        //A SUPPRIMER : l'élève choisira
        $demande->setStockage("kh");        
        //A SUPPRIMER : l'élève choisira
        $demande->setTemplate("lh");        
        //A SUPPRIMER : l'élève choisira
        $demande->setRam(3);        
        //A SUPPRIMER : l'élève choisira
        $demande->setDisque(567);        
        //A SUPPRIMER : l'élève choisira
        $demande->setSwap(67);
        //A SUPPRIMER : l'élève choisira
        $demande->setNbrCpu(6);
        //A SUPPRIMER : l'élève choisira
        $demande->setAdrsReseau("ytyuijk");        
        //A SUPPRIMER : l'élève entrera l'adresse mail qu'il veut
        $demande->setMailEleve("salut@gmail.com");
        
        //On crée le forumlaire grace au formbuilder et au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder('form', $demande);
        
        //On ajoute les champs de l'entité que l'on veut à notre formulaire
        $form = $this->get('form.factory')->createBuilder('form', $demande)
                ->add('nom', 'text')
                ->add('pool',   'choice', array('choices' => Demande::getListePool()))
                ->add('stockage',   'choice', array('choices' => Demande::getListeStockage()))
              ->add('template',   'choice', array('choices' => Demande::getListeTemplate()))
               ->add('ram',   'choice', array('choices' => Demande::getListeRam()))
               ->add('disque',   'choice', array('choices' => Demande::getListeDisque()))
                ->add('swap',   'choice', array('choices' => Demande::getListeSwap()))
                ->add('nbrCpu',   'choice', array('choices' => Demande::getListeNbrCpu()))
                ->add('adrsReseau',   'choice', array('choices' => Demande::getListeAdrsReseau()))
                ->add('professeur',   'choice', array('choices' => Demande::getListeProfesseur()))
                ->add('mailEleve',   'email')
                ->getForm()
        ;
        
        
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $demande contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
          // On l'enregistre notre objet $advert dans la base de données, par exemple
          $em = $this->getDoctrine()->getManager();
          $em->persist($demande);
          $em->flush();

          //LE PROFESSEUR RECOIT UN MAIL
          
          
          $request->getSession()->getFlashBag()->add('notice', 'Demande bien enregistrée.');
        
        // On redirige vers la page de visualisation de la VM nouvellement créée
        return $this->redirect($this->generateUrl('glpmr_virtual_machine_consultation'));

    }
        
        return $this->render('GlpmrVirtualMachineBundle:Default:creation.html.twig',
                array('form' => $form->createView(),
        ));
    }
    
    public function consultationAction()
    {
        # todo
//        $repository = $this
//            ->getDoctrine()
//            ->getManager()
//            ->getRepository('OCP:Advert');
//        
//        $demande = $repository->findBy(array('idEleve' => 9999));
        
        return $this->render('GlpmrVirtualMachineBundle:Default:consultation.html.twig');
    }
}
