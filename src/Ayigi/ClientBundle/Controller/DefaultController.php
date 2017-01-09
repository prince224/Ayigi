<?php

namespace Ayigi\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AyigiClientBundle:Default:index.html.twig');
    }
}
