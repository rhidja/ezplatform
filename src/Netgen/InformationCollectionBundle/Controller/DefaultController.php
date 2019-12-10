<?php

namespace Netgen\InformationCollectionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NetgenInformationCollectionBundle:Default:index.html.twig');
    }
}
