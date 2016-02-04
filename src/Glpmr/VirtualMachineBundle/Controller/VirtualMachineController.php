<?php

namespace Glpmr\VirtualMachineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Glpmr\VirtualMachineBundle\Entity\User as User;
use Glpmr\VirtualMachineBundle\Entity\Demande;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Glpmr\AuthentificationBundle\Entity\AuthentificationLDAP;

class VirtualMachineController extends Controller {

    public function creationAction(Request $request, $id) {
        
        //On récupère l'id de l'utilisateur et on check si c'est un prof ou un eleve
//        $eleve = new User();
//        $eleve = $this->getUserCourant(true);
//        //On rempli l'objet user avec les infos de l'active directory                
//        //On crée le nouvel objet demande
        $demande = new Demande();
        $eleve = new User();
//
//        $isEleve = true;
//
//        $demande->setEleve($eleve);
        //On se connecte à l'AD et on met à jour les prof qui sont dessus si besoin
        $this->gestionProfEnBase();

        //Si id != 0 c'est le prof qui vient de cliquer sur le mail qui doit valider à nouveau
        if ($id != 0) {
            $em = $this->getDoctrine()->getManager();
            $DemandeCree = new Demande();
            $DemandeCree = $em->getRepository('GlpmrVirtualMachineBundle:Demande')->findBy(array('id' => $id));

            //var_dump($DemandeCree);

            $demande = $DemandeCree[0];

            $prof = new User();
            $prof = $this->getUserCourant(false);
            //var_dump($prof);
            $prof = $this->getProfWithId($prof);
            
            //var_dump($prof);
            //var_dump("===================================================");
            
            $demande->setProf($prof);

            $demande = $this->getDoctrine()->getManager()->merge($demande);
            
            //var_dump($demande);
            
            //On ajoute les champs de l'entité que l'on veut à notre formulaire
            $form = $this->get('form.factory')->createBuilder('form', $demande)
                    ->add('nomVM', 'text')
                    ->add('mailEleve', 'email')
                    ->add('passwordRoot', 'text')
                    ->add("OS", "entity", array(
                        "label" => "Choix OS",
                        "class" => "GlpmrVirtualMachineBundle:Os",
                    ))
                    ->add("adrsReseau", "entity", array(
                        "label" => "Choix adresse réseau",
                        "class" => "GlpmrVirtualMachineBundle:AdresseIp",
                    ))
                    ->getForm()
            ;
            //var_dump($demande);
        } 
        //Si eleve
        else {
            //On récupère l'id de l'utilisateur et on check si c'est un prof ou un eleve
            $eleve = new User();
            $eleve = $this->getUserCourant(true);
            $isEleve = true;

            $demande->setNomEleve($eleve->getNom());
            $demande->setPrenomEleve($eleve->getPrenom());
            $demande->setMailEleve($eleve->getMail());

            //On ajoute les champs de l'entité que l'on veut à notre formulaire
            $form = $this->get('form.factory')->createBuilder('form', $demande)
                    ->add('nomVM', 'text')
                    ->add('mailEleve', 'email')
                    ->add('passwordRoot', 'password')
                    ->add("OS", "entity", array(
                        "label" => "Choix OS",
                        "class" => "GlpmrVirtualMachineBundle:Os",
                    ))
                    ->add("adrsReseau", "entity", array(
                        "label" => "Choix adresse réseau",
                        "class" => "GlpmrVirtualMachineBundle:AdresseIp",
                    ))
                    ->add("prof", "entity", array(
                        "label" => "Choix professeur",
                        "class" => "GlpmrVirtualMachineBundle:User",
                    ))
                    ->getForm()
            ;
        }

        //var_dump($demande);
        // On fait le lien Requête <-> Formulaire
        // À partir de maintenant, la variable $demande contient les valeurs entrées dans le formulaire par le visiteur
        $form->handleRequest($request);

        // On vérifie que les valeurs entrées sont correctes
        if ($form->isValid()) {
            
            $retour = "Rien à voir";
            
            // On l'enregistre notre objet $advert dans la base de données, par exemple
            $em = $this->getDoctrine()->getManager();
            
            if($id == 0)
            {
                $demande->setPrenomEleve($eleve->getPrenom());
                $demande->setNomEleve($eleve->getNom());
            }
            
            $em->persist($demande);
            $em->flush();

            //Si l'eleve fait une demande
            if ($id == 0) {
                $id = $demande->getId();

                //On envoie le mail au prof
                $message = \Swift_Message::newInstance()
                        ->setSubject('Demande de création de VM')
                        ->setFrom('glpmremailvm@gmail.com')
                        ->setTo($demande->getProf()->getMail()) //remplacer par le mail du prof
                        ->setBody($this->renderView('GlpmrVirtualMachineBundle:Default:mailProf.txt.twig', array(
                            'professeur' => $demande->getProf()->getNom() . " " . $demande->getProf()->getPrenom(),
                            'eleve' => $demande->getNomEleve() . " " . $demande->getPrenomEleve(),
                            'password' => $demande->getPasswordRoot(),
                            'OS' => $demande->getOS(),
                            'lien' => $this->generateUrl('glpmr_virtual_machine_creation', array(
                                'id' => $demande->getId()), true))
                        ), 'text/html')
                ;
                
                $this->get('mailer')->send($message);

                $retour = "Création";
                
                $eleve = true;
            }
            //Si c'est le prof qui fait la demande
            else {
                
                $professeur = $demande->getProf();                               
                $stringProf = $professeur->getNom() . " " . $professeur->getPrenom();
                //var_dump($stringProf);
                
                //On envoie le mail à l'eleve
                $message = \Swift_Message::newInstance()
                        ->setSubject('Demande de création de VM')
                        ->setFrom('glpmremailvm@gmail.com')
                        ->setTo($demande->getMailEleve()) //remplacer par le mail de l'eleve
                        ->setBody($this->renderView('GlpmrVirtualMachineBundle:Default:mailEleve.txt.twig', array(
                            'professeur' => $stringProf,
                            'eleve' => $demande->getNomEleve() . " " . $demande->getPrenomEleve(),
                            'demande' => $demande)
                        ), 'text/html')
                ;
                $this->get('mailer')->send($message);
                
                //on envoie une copie à obi
                $message = \Swift_Message::newInstance()
                        ->setSubject('Demande de création de VM (Copie)')
                        ->setFrom('glpmremailvm@gmail.com')
                        ->setTo('o.bailly@glpmr.fr') //remplacer par le mail de l'eleve
                        ->setBody($this->renderView('GlpmrVirtualMachineBundle:Default:mailEleve.txt.twig', array(
                            'professeur' => $stringProf,
                            'eleve' => $demande->getNomEleve() . " " . $demande->getPrenomEleve(),
                            'demande' => $demande)
                        ), 'text/html')
                ;
                $this->get('mailer')->send($message);
                
                
                
                $osTemplate = $demande->getOs()->getLibelleProxMox();                
                $ipAdresse = $demande->getAdrsReseau()->getAdresse();
                $hostname = $demande->getNomVM();
                $password = $demande->getPasswordRoot();

                //On execute la methode qui se connecter en ssh au serveur puis executer le script de création de la vm
                $retour = exec("\glpmr-portail.reseau-labo.fr\web\assets\shell\proxmox.sh" . " " . $osTemplate . " " . $ipAdresse . " " . $hostname . " " . $password, $retour);
                
                var_dump("Retour Exec() : ");
                var_dump($retour);
            }

            // On redirige vers la page de visualisation de la VM nouvellement créée
           return $this->redirect($this->generateUrl('glpmr_virtual_machine_consultation', array(
                    'id' => $id,
                    'eleve' => $eleve,
                    'retour' => $retour))); 
        }

        return $this->render('GlpmrVirtualMachineBundle:Default:creation.html.twig', array(
                    'form' => $form->createView(),
                    'demande' => $demande
        ));
    }

    private function gestionProfEnBase() {
        $entity = new User();
        $session = new Session();
        $login = $session->get('username');
        $password = $session->get('password');
        //On se connecte à l'AD, on chop tous les profs et leurs mails
        $lstProfAD = AuthentificationLDAP::getListeProf($login, $password);

        $em = $this->getDoctrine()->getManager();

        //On vire les profs qui sont en BDD mais plus dans l'AD
        $lstProfBDD = $em->getRepository('GlpmrVirtualMachineBundle:User')
                ->findAll();

        //var_dump($lstProfBDD);

        $listAcomparer = array();

        foreach ($lstProfBDD as $prof) {
            array_push($listAcomparer, clone $prof);
        }

        foreach ($listAcomparer as $prof) {
            $prof->setId(null);
        }

        //On regarde tous les profs de la  BDD
        foreach ($listAcomparer as $profBDD) {

            //Si le prof de la BDD n'est pas contenu dans l'AD
            if (!in_array($profBDD, $lstProfAD)) {

                $em = $this->container->get('doctrine')->getManager();
                $connection = $em->getConnection();
                $statement = $connection->prepare(
                        "DELETE FROM User "
                        . "WHERE nom = :nom "
                        . "AND prenom = :prenom");
                $statement->bindValue('nom', $profBDD->getNom());
                $statement->bindValue('prenom', $profBDD->getPrenom());
                $statement->execute();
            }
        }

        //Ensuite on met en BDD tous les prof qui sont dans l'AD
        foreach ($lstProfAD as $prof) {
            //On cherche si l'enregistrement existe            
            $entity = $em->getRepository('GlpmrVirtualMachineBundle:User')
                    ->findOneBy(
                    $truc = array(
                'nom' => $prof->getNom(),
                'prenom' => $prof->getPrenom()
            ));

            //Si n'existe pas on l'ajoute
            if ($entity == null) {
                //var_dump("n'existe pas encore, ajout en BDD");
                //var_dump($prof);
                $em->persist($prof);
                $em->flush();
            }
            //Si il existe déjà
            else {
                //Si l'adresse mail à changé
                if ($entity->getMail() != $prof->getMail()) {
                    $entity->setMail($prof->getMail());
                    $em->flush();
                    //var_dump("Existe déjà, mise a jour en BDD");
                }
            }
        }
    }

    private function getProfWithId($user) {
        $entity = new User();
        $session = new Session();
        $login = $session->get('username');
        $password = $session->get('password');

        $em = $this->getDoctrine()->getManager();

        //On cherche tous les profs dans la bdd
        $lstProfBDD = $em->getRepository('GlpmrVirtualMachineBundle:User')
                ->findAll();        
        
        //var_dump($user);
        
        foreach ($lstProfBDD as $profBDD) {
            if ($profBDD->getNom() == $user->getNom() &&
                    $profBDD->getPrenom() == $user->getPrenom() &&
                    $profBDD->getMail() == $user->getMail()) {
                $user->setId($profBDD->getId());
                //var_dump($user);
            }
        }
        return $user;
    }

    private function getUserCourant($isEleve) {
        $session = new Session();
        $login = $session->get('username');
        $password = $session->get('password');

        $user = new User();
        //On se connecte à l'AD, on chop tous les profs et leurs mails
        $user = AuthentificationLDAP::getUserCourant($login, $password, $isEleve);

        //var_dump($user);
        
        //Si c'est un prof on récupère l'ID
        if (!$isEleve) {
            //On récupère les utilisateurs en BDD
            $em = $this->getDoctrine()->getManager();
            //On vire les profs qui sont en BDD mais plus dans l'AD
            $lstProfBDD = $em->getRepository('GlpmrVirtualMachineBundle:User')
                    ->findAll();

            //On parcours tous les utilisateurs 
            foreach ($lstProfBDD as $profBDD) {
                //On cherche le meme
                if ($profBDD->getNom() == $user->getNom() &&
                        $profBDD->getPrenom() == $user->getPrenom() &&
                        $profBDD->getMail() == $user->getMail()) {
                    //On set l'ID
                    $user->setId($profBDD->getId());
                }
            }
        }
        //var_dump($user);

        return $user;
    }

    public function consultationAction(Request $request) {

        $id = $request->get("id");
        $eleve = $request->get("eleve");
        $retour = $request->get("retour");
                
      //  var_dump($id);
      //  var_dump($eleve);
      //  var_dump($retour);
        
        $em = $this->getDoctrine()->getManager();
        $DemandeCree = new Demande();
        $DemandeCree = $em->getRepository('GlpmrVirtualMachineBundle:Demande')->findBy(array('id' => $id));

        $demande = $DemandeCree[0];
        $eleve = $request->get('eleve');

        //Si c'est un eleve
        if ($eleve) {
            return $this->render('GlpmrVirtualMachineBundle:Default:consultationEleve.html.twig', array(
                        'id' => $id,
                        'demande' => $demande,
                        'lien' => $this->generateUrl('glpmr_virtual_machine_creation', array('id' => 0))));
        } else {
            return $this->render('GlpmrVirtualMachineBundle:Default:consultationProf.html.twig', array(
                        'id' => $id,
                        'demande' => $demande,
                        'lien' => $this->generateUrl('glpmr_virtual_machine_creation', array('id' => 0))));
        }
    }

    public function gestionAction(Request $request) {

        //Si on post le formulaire d'ajout d'un nouvel OS
        $this->ajouterOsBDD($request);
        //Si on post le formulaire d'ajout d'une nouvelle adresse IP
        $this->ajouterIpBDD($request);

        // récupere les os et les adresses ip pour les afficher
        $listeOS = $this->getOs();
        $listeAdresseIp = $this->getAdressesIp();
//        var_dump($listeOS);
//        var_dump($listeAdresseIp);

        return $this->container->get('templating')->renderResponse('GlpmrVirtualMachineBundle:Default:gestion.html.twig', array(
                    'listeAdresseIp' => $listeAdresseIp,
                    'listeOS' => $listeOS
        ));
    }

    public function suppressionIPAction(Request $request, $id) {
        //On ajoute un nouvel enregistrement en base
        $em = $this->container->get('doctrine')->getManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare(
                "DELETE FROM AdresseIp "
                . "WHERE id = :id");
        $statement->bindValue('id', $id);
        $statement->execute();

        $listeAdresseIp = $this->getAdressesIp();
        return $this->render('GlpmrVirtualMachineBundle:Default:AJAX_ip.html.twig', array("listeAdresseIp" => $listeAdresseIp));
        //return $this->gestionAction($request);
    }

    public function suppressionOSAction(Request $request, $id) {
        //On ajoute un nouvel enregistrement en base
        $em = $this->container->get('doctrine')->getManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare(
                "DELETE FROM Os "
                . "WHERE id = :id");
        $statement->bindValue('id', $id);
        $statement->execute();

        return $this->gestionAction($request);
    }

    private function getAdressesIp() {
        $em = $this->container->get('doctrine')->getManager();

        $listeAdresseIp = $em->createQuery(
                        'SELECT p FROM GlpmrVirtualMachineBundle:AdresseIp p ORDER BY p.adresse ASC'
                )
                ->getResult();

        return $listeAdresseIp;
    }

    private function getOs() {
        $em = $this->container->get('doctrine')->getManager();

        $listeOS = $em->createQuery(
                        'SELECT p FROM GlpmrVirtualMachineBundle:Os p ORDER BY p.libelleUser ASC'
                )
                ->getResult();

        return $listeOS;
    }

    private function ajouterOsBDD(Request $request) {
        $champ_libelleProxmox = $request->get('libelle_proxmox');
        $champ_libelleUtilisateur = $request->get('libelle_utilisateur');

        if ($champ_libelleProxmox != null && $champ_libelleUtilisateur != null) {
            //On ajoute un nouvel enregistrement en base
            $em = $this->container->get('doctrine')->getManager();
            $connection = $em->getConnection();


            $statement = $connection->prepare(
                    "INSERT INTO Os (libelleProxMox, libelleUser) "
                    . "VALUES(:proxmox, :utilisateur) ");
            $statement->bindValue('proxmox', $champ_libelleProxmox);
            $statement->bindValue('utilisateur', $champ_libelleUtilisateur);
            $statement->execute();
        }
    }

    private function ajouterIpBDD(Request $request) {
        $champ_newAdresseIp = $request->get('newAdresseIp');

        if ($champ_newAdresseIp != null) {
            //On ajoute un nouvel enregistrement en base
            $em = $this->container->get('doctrine')->getManager();
            $connection = $em->getConnection();


            $statement = $connection->prepare(
                    "INSERT INTO AdresseIp (adresse) "
                    . "VALUES(:adresse) ");
            $statement->bindValue('adresse', $champ_newAdresseIp);
            $statement->execute();
        }
    }

}
