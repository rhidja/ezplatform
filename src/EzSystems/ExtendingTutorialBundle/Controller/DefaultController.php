<?php

namespace EzSystems\ExtendingTutorialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EzSystemsExtendingTutorialBundle:Default:index.html.twig');
    }
}
