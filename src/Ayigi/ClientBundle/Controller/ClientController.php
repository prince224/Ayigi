<?php

namespace Ayigi\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Ayigi\ClientBundle\Entity\Client;
use Ayigi\ClientBundle\Form\ClientType;

use Ayigi\PlateFormeBundle\Entity\Service;
use Ayigi\PlateFormeBundle\Form\ServiceType;

use Ayigi\ClientBundle\Entity\PaiementDone;
use Ayigi\ClientBundle\Form\PaiementDoneType;

use Ayigi\EtablissementBundle\Entity\Etablissement;
use Ayigi\EtablissementBundle\Form\EtablissementType;


class ClientController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$client = $em->getRepository('AyigiClientBundle:Client')->find(1);

        return $this->redirect($this->generateUrl('ayigi_client_historique_user', array(
                    'id' => $client->getId(),
                    )));
    }


    //--------------------- Manage plateforme client --------------------------------------------------------------
    public function createCompteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $utilisateur = new Client();

        $form = $this->get('form.factory')->create(ClientType::class, $utilisateur);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $utilisateur= $form->getData();

                $factory = $this->container->get('security.encoder_factory');
                $encoder = $factory->getEncoder($utilisateur);
                $utilisateur->encodePassword($encoder);

                $em->persist($utilisateur);
                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_client_homepage'));
            }
        }
        return $this->render('AyigiClientBundle:Client:createCompte.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    public function updateUserAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('AyigiClientBundle:Client')->find($id);
        
        $form = $this->get('form.factory')->create(ClientType::class, $client);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $user= $form->getData();

                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_client_homepage'));
            }
        }
        return $this->render('AyigiClientBundle:Client:updateUser.html.twig', array(
            'form' => $form->createView(),
            'client' => $client,
            ));
    }


    public function choixPaysEtablissementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $etablissements = new Etablissement();

        if($request->isXmlHttpRequest()) // pour vérifier la présence d'une requete Ajax
        {
            $idPays = $request->request->get('id');

            $pays = $em->getRepository('AyigiPlateFormeBundle:PaysDestination')->find($idPays);

            $selecteur = $request->request->get('select');
                   
            if ($pays != null)
            {  
                $data = $em->getRepository('AyigiEtablissementBundle:Etablissement')->EtablissementPays($idPays);

                //$data = json_encode($etablissements);
                return new JsonResponse($data);
            }
        }
        return new Response("Nonnn ...."); 
        ;
    }


    public function choixEtablissementServiceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $etablissements = new Etablissement();

        if($request->isXmlHttpRequest()) // pour vérifier la présence d'une requete Ajax
        {
            $idEtablissement = $request->request->get('id');
            $selecteur = $request->request->get('select');
                   
            if ($idEtablissement != null)
            {  
                $data = $em->getRepository('AyigiPlateFormeBundle:Service')->ServiceEtablissement($idEtablissement);

                //$data = json_encode($etablissements);
                return new JsonResponse($data);
            }
        }
        return new Response("Nonnn ...."); 
        ;
    }

    public function historiqueUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $client = $em->getRepository('AyigiClientBundle:Client')->find($id);
        $listePaiements = new PaiementDone();
        $paiement = new PaiementDone();

        $allPays = $em->getRepository('AyigiPlateFormeBundle:PaysDestination')->findAll();
        $allServices = $em->getRepository('AyigiPlateFormeBundle:Service')->findAll();


        if ($client != null)
        {
            $listePaiements = $em->getRepository('AyigiClientBundle:PaiementDone')->findClientPaiement($client->getId());
            
            if($listePaiements != null)
            {
                return $this->render('AyigiClientBundle:Client:historiqueUser.html.twig', array(
                    'client' => $client,
                    'paiements' => $listePaiements,
                ));
            }
            else
            {
                return $this->redirect($this->generateUrl('ayigi_client_paiement_user', array(
                    'idClient' => $client->getId(),
                    )));
            }

        }

        return $this->render('AyigiClientBundle:Client:historiqueUser.html.twig', array(
            'client' => $client,
            'paiements' => $listePaiements,
            ));
    }

    public function paiementServiceAction(Request $request, $idClient)
    {
        $em = $this->getDoctrine()->getManager();

        $client = $em->getRepository('AyigiClientBundle:Client')->find($idClient);

        $paiement = new PaiementDone();
        $listedesdevises = $em->getRepository('AyigiClientBundle:devise')->findAll();

        $montantPayer = null;

        $form = $this->get('form.factory')->create(PaiementDoneType::class, $paiement);

        if ($client != null)
        {
            if($request->getMethod() == 'POST')
            {
                //$devise = $request->request->get('choixdevise');

                $form->handleRequest($request);
            
                $paiement = $form->getData();

                $paiement->setClient($client);
                //$paiement->SetDevise($devise);

                $montantPayer = $paiement->getMontant();
                if($montantPayer != null)
                {
                    $paiement->SetEtat(true);
                }

                $em->persist($paiement);
                $em->flush();

                return $this->redirect($this->generateUrl('ayigi_client_finaliser_paiement_user_carte_bancaire', array(
                'idPaiement' => $paiement->getId(),
                )));
            }
            
            return $this->render('AyigiClientBundle:Client:paiementService.html.twig', array(
                'client' => $client,
                'form' => $form->createView(),
                'listedesdevises' => $listedesdevises,
            ));
        }   
        return new Response('NON NON');
    }

    public function FinaliserPaiementServiceCarteBancaireAction(Request $request, $idPaiement)
    {
        $em = $this->getDoctrine()->getManager();

        $paiement = $em->getRepository('AyigiClientBundle:PaiementDone')->find($idPaiement);
        $client = $paiement->getClient();

        $form = $this->get('form.factory')->create(PaiementDoneType::class, $paiement);

        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            
            $paiement = $form->getData();

            $em->persist($paiement);
            $em->flush();

            return $this->redirect($this->generateUrl('ayigi_client_historique_user', array(
                'id' => $paiement->getClient()->getId(),
                )));
        }
            
        return $this->render('AyigiClientBundle:Client:FinaliserPaiementServiceCarteBancaire.html.twig', array(
            'form' => $form->createView(),
            'paiement' => $paiement,
            'client' => $client,
        ));
    }//fin finaliser paiement par carte bancaire

    public function validateInfoGeneralesPaiementServiceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $paiement = new PaiementDone();

        if($request->isXmlHttpRequest()) // pour vérifier la présence d'une requete Ajax
        {
            $idPays = $request->request->get('pays');
            $idEtablissement = $request->request->get('etablissement');
            $idService = $request->request->get('service');
            $message = $request->request->get('message');
            $nomDestinataire = $request->request->get('nomDestinataire');
            $prenomDestinataire = $request->request->get('prenomDestinataire');
            $telephoneDestinataire = $request->request->get('telephoneDestinataire');
            
            $pays = $em->getRepository('AyigiPlateFormeBundle:PaysDestination')->find($idPays);
            $service = $em->getRepository('AyigiPlateFormeBundle:Service')->find($idService);

            if ($pays != null)
            {  
                $paiement->setPaysDestination($pays);
            }

            if ($service != null)
            {  
                $paiement->setService($service);
            }

            if ($nomDestinataire != null)
            {  
                $paiement->setNom($nomDestinataire);
            }

            if ($prenomDestinataire != null)
            {  
                $paiement->setPrenom($prenomDestinataire);
            }

            if ($telephoneDestinataire != null)
            {  
                $paiement->setTelephone($telephoneDestinataire);
            }

            if ($message != null)
            {  
                $paiement->setMessage($message);
            }

            $em->persist($paiement);
            $em->flush();

            $data = $pays->getNom();

            //$data = json_encode($etablissements);
            return new Response($data);
            
        }
        return new Response("Erreur de validation des informations générales"); 
        ;
    }
    //fin validate infos générales

    public function reprisePaiementServiceAction(Request $request, $idPaiement)
    {
        $em = $this->getDoctrine()->getManager();

        $paiement = $em->getRepository('AyigiClientBundle:PaiementDone')->find($idPaiement);
        $client = $paiement->getClient();
        $listedesdevises = $em->getRepository('AyigiClientBundle:devise')->findAll();

        $montantPayer = null;

        $form = $this->get('form.factory')->create(PaiementDoneType::class, $paiement);

        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);
            
            $paiement = $form->getData();

            $montantPayer = $paiement->getMontant();
            if($montantPayer != null)
            {
                $paiement->SetEtat(true);
            }

            $em->persist($paiement);
            $em->flush();

            return $this->redirect($this->generateUrl('ayigi_client_historique_user', array(
                'id' => $paiement->getClient()->getId(),
                )));
        }
            
        return $this->render('AyigiClientBundle:Client:paiementService.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
            'listedesdevises' => $listedesdevises,
        ));
    }

    public function viewUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('AyigiClientBundle:Client')->find($id);

        if ($user != null)
        {
            $em->remove($user);
            $em->flush();
                
           return $this->render('AyigiClientBundle:Client:viewUser.html.twig', array(
            'user' => $user,
            ));
        }
        
        return $this->redirect($this->generateUrl('ayigi_client_homepage'));
    }
}
