<?php
declare(strict_types=1);

namespace Netgen\InformationCollectionBundle\Services;


use eZ\Publish\API\Repository\Repository;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class InformationCollectionService
 * @package Netgen\InformationCollectionBundle\Services
 */
class InformationCollectionService
{
    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    protected $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getCollectedInformation()
    {
        $canRead = $this->repository
            ->getPermissionResolver()
            ->hasAccess(
                'infocollector',
                'read'
            );

        if ($canRead === false) {
            throw new AccessDeniedException();
        }

        // do something
    }

    public function getCollectedInformationByUser(int $userId)
    {
        $user = $this->repository
            ->getUserService()
            ->loadUser($userId);

        $canRead = $this->repository
            ->getPermissionResolver()
            ->hasAccess(
                'infocollector',
                'read',
                $user
            );

        if ($canRead === false) {
            throw new AccessDeniedException();
        }

        // do something
    }
}
