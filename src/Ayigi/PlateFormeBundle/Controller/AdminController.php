<?php

namespace Ayigi\PlateFormeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Ayigi\PlateFormeBundle\Entity\Administrateur;
use Ayigi\PlateFormeBundle\Form\AdministrateurType;
use Ayigi\PlateFormeBundle\Entity\PaysDestination;
use Ayigi\PlateFormeBundle\Form\PaysDestinationType;
use Ayigi\PlateFormeBundle\Entity\Service;
use Ayigi\PlateFormeBundle\Form\ServiceType;

use Ayigi\EtablissementBundle\Entity\Etablissement;
use Ayigi\EtablissementBundle\Form\EtablissementType;


class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('AyigiPlateFormeBundle:Admin:index.html.twig');
    }

//--------------------- Manage plateforme User --------------------------------------------------------------
    public function createUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $adminUser = new Administrateur();

        $form = $this->get('form.factory')->create(AdministrateurType::class, $adminUser);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $adminUser= $form->getData();

                $factory = $this->container->get('security.encoder_factory');
                $encoder = $factory->getEncoder($adminUser);
                $adminUser->encodePassword($encoder);

                $em->persist($adminUser);
                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_user'));
            }
        }
        return $this->render('AyigiPlateFormeBundle:Admin:createUser.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    public function updateUserAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $adminUser = $em->getRepository('AyigiPlateFormeBundle:Administrateur')->find($id);
        
        $form = $this->get('form.factory')->create(AdministrateurType::class, $adminUser);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $adminUser= $form->getData();

                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_user'));
            }
        }
        return $this->render('AyigiPlateFormeBundle:Admin:updateUser.html.twig', array(
            'form' => $form->createView(),
            'adminUser' => $adminUser,
            ));
    }

    public function deleteUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $adminUser = $em->getRepository('AyigiPlateFormeBundle:Administrateur')->find($id);

        if ($adminUser != null)
        {
            $em->remove($adminUser);
            $em->flush();
                
            return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_user')); 
        }
        
        return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_homepage'));
    }

    public function listeUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = new Administrateur();

        $users = $em->getRepository('AyigiPlateFormeBundle:Administrateur')->findAll();

        if ($users != null)
        {
            return $this->render('AyigiPlateFormeBundle:Admin:listeUser.html.twig', array(
                'users'  => $users,
                ));             
        }
        else
        {
            return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_homepage'));
        }
    }

//------------------------- End manage plateforme users ----------------------------------------------------

//--------------------- Manage country --------------------------------------------------------------
    public function createCountryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $country = new PaysDestination();

        $form = $this->get('form.factory')->create(PaysDestinationType::class, $country);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $country= $form->getData();

                $em->persist($country);
                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_country'));
            }
        }
        return $this->render('AyigiPlateFormeBundle:Admin:createCountry.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    public function updateCountryAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
      

        $country = $em->getRepository('AyigiPlateFormeBundle:PaysDestination')->find($id);
        
        $form = $this->get('form.factory')->create(PaysDestinationType::class, $country);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $country= $form->getData();

                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_country'));
            }
        }
        return $this->render('AyigiPlateFormeBundle:Admin:updateCountry.html.twig', array(
            'form' => $form->createView(),
            'country' => $country,
            ));
    }

    public function deleteCountryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $country = $em->getRepository('AyigiPlateFormeBundle:PaysDestination')->find($id);

        if ($country != null)
        {
            $em->remove($country);
            $em->flush();
                
            return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_country')); 
        }
        
        return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_homepage'));
    }

    public function listeCountryAction()
    {
        $em = $this->getDoctrine()->getManager();
        $countries = new Administrateur();

        $countries = $em->getRepository('AyigiPlateFormeBundle:PaysDestination')->findAll();

        if ($countries != null)
        {
            return $this->render('AyigiPlateFormeBundle:Admin:listeCountry.html.twig', array(
                'countries'  => $countries,
                ));             
        }
        else
        {
            return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_homepage'));
        }
    }

//------------------------- End manage country ----------------------------------------------------




//--------------------- Manage Etablissement --------------------------------------------------------------
    public function createEtablissementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $etablissement = new Etablissement();

        $form = $this->get('form.factory')->create(EtablissementType::class, $etablissement);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $etablissement= $form->getData();

                $em->persist($etablissement);
                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_etablissement'));
            }
        }
        return $this->render('AyigiPlateFormeBundle:Admin:createEtablissement.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    public function updateEtablissementAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
      
        $etablissement = $em->getRepository('AyigiEtablissementBundle:Etablissement')->find($id);
        
        $form = $this->get('form.factory')->create(EtablissementType::class, $etablissement);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $etablissement= $form->getData();

                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_etablissement'));
            }
        }
        return $this->render('AyigiPlateFormeBundle:Admin:updateEtablissement.html.twig', array(
            'form' => $form->createView(),
            'etablissement' => $etablissement,
            ));
    }

    public function deleteEtablissementAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $etablissement = $em->getRepository('AyigiEtablissementBundle:Etablissement')->find($id);

        if ($etablissement != null)
        {
            $em->remove($etablissement);
            $em->flush();
                
            return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_etablissement')); 
        }
        
        return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_homepage'));
    }

    public function listeEtablissementAction()
    {
        $em = $this->getDoctrine()->getManager();
        $etablissements = new Administrateur();

        $etablissements = $em->getRepository('AyigiEtablissementBundle:Etablissement')->findAll();

        if ($etablissements != null)
        {
            return $this->render('AyigiPlateFormeBundle:Admin:listeEtablissement.html.twig', array(
                'etablissements'  => $etablissements,
                ));             
        }
        else
        {
            return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_homepage'));
        }
    }

//------------------------- End manage Etablissement ----------------------------------------------------


//--------------------- Manage Service --------------------------------------------------------------
    public function createServiceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $service = new Service();

        $form = $this->get('form.factory')->create(ServiceType::class, $service);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $service= $form->getData();

                $em->persist($service);
                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_service'));
            }
        }
        return $this->render('AyigiPlateFormeBundle:Admin:createService.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    public function updateServiceAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
      
        $service = $em->getRepository('AyigiPlateFormeBundle:Service')->find($id);
        
        $form = $this->get('form.factory')->create(ServiceType::class, $service);

        if ($request->getMethod()=='POST') {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $service= $form->getData();

                $em->flush();
                
                return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_service'));
            }
        }
        return $this->render('AyigiPlateFormeBundle:Admin:updateService.html.twig', array(
            'form' => $form->createView(),
            'service' => $service,
            ));
    }

    public function deleteServiceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $service = $em->getRepository('AyigiPlateFormeBundle:Service')->find($id);

        if ($service != null)
        {
            $em->remove($service);
            $em->flush();
                
            return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_liste_service')); 
        }
        
        return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_homepage'));
    }

    public function listeServiceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $services = new Service();

        $services = $em->getRepository('AyigiPlateFormeBundle:Service')->findAll();

        if ($services != null)
        {
            return $this->render('AyigiPlateFormeBundle:Admin:listeService.html.twig', array(
                'services'  => $services,
                ));             
        }
        else
        {
            return $this->redirect($this->generateUrl('ayigi_plate_forme_admin_homepage'));
        }
    }

//------------------------- End manage Service ----------------------------------------------------



}//End controler