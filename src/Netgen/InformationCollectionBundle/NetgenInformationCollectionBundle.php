<?php

namespace Netgen\InformationCollectionBundle;

use Netgen\InformationCollectionBundle\PolicyProvider\InformationCollectionYamlPolicyProvider;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NetgenInformationCollectionBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $eZExtension = $container->getExtension('ezpublish');
        $eZExtension->addPolicyProvider(new InformationCollectionYamlPolicyProvider());
    }
}
