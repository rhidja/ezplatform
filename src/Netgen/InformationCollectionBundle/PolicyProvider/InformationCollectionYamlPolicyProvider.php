<?php
declare(strict_types=1);

namespace Netgen\InformationCollectionBundle\PolicyProvider;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Security\PolicyProvider\YamlPolicyProvider;

class InformationCollectionYamlPolicyProvider extends YamlPolicyProvider
{
    protected function getFiles()
    {
        return [
            __DIR__ . '/../Resources/config/policies.yml',
        ];
    }
}