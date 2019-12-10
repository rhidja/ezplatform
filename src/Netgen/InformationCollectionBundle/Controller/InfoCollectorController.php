<?php
declare(strict_types=1);

namespace Netgen\InformationCollectionBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\Symfony\Security\Authorization\Attribute;
use Symfony\Component\HttpFoundation\Response;

class InfoCollectorController extends Controller
{
    public function showAction()
    {
        $attribute = new Attribute('infocollector', 'read');
        $this->denyAccessUnlessGranted($attribute);
        return new Response("You are allowed to read collected information.");
    }
}