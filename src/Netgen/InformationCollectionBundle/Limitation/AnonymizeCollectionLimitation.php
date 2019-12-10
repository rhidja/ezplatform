<?php

declare(strict_types=1);

namespace Netgen\InformationCollectionBundle\Limitation;

use eZ\Publish\API\Repository\Values\User\Limitation;

class AnonymizeCollectionLimitation extends Limitation
{
    /**
     * {@inheritDoc}
     */
    public function getIdentifier(): string
    {
        return 'AnonymizeCollection';
    }
}