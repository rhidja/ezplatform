<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use eZ\Publish\API\Repository\Exceptions\ContentFieldValidationException;
use eZ\Publish\API\Repository\Exceptions\ContentValidationException;
use eZ\Publish\API\Repository\Exceptions\InvalidArgumentException;
use eZ\Publish\API\Repository\Exceptions\UnauthorizedException;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\Core\FieldType\Image\Value;
use Symfony\Component\HttpKernel\KernelInterface;

class AppFixtures extends Fixture
{
    const ALL_RIDES_LOCATION_ID = 54;

    /**
     * @var \eZ\Publish\API\Repository\ContentService
     */
    protected $contentService;

    /**
     * @var \eZ\Publish\API\Repository\ContentTypeService
     */
    protected $contentTypeService;

    /**
     * @var \eZ\Publish\API\Repository\LocationService
     */
    protected $locationService;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var Repository
     */
    protected $repository;

    public function __construct(Repository $repository, KernelInterface $kernel)
    {
        $this->repository = $repository;
        $this->contentService = $repository->getContentService();
        $this->contentTypeService = $repository->getContentTypeService();
        $this->locationService = $repository->getLocationService();
        $this->kernel = $kernel;
    }

    /**
     * @param ObjectManager $manager
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function load(ObjectManager $manager)
    {
        $rideContentType = $this->contentTypeService->loadContentTypeByIdentifier('Ride');

        foreach ($this->rideDetails() as $rideDetail){
            $this->repository->sudo(function (Repository $repository) use ($rideDetail, $rideContentType) {
                $rideContentCreateStruct = $this->contentService->newContentCreateStruct($rideContentType, 'eng-GB');
                $rideContentCreateStruct->setField('name', $rideDetail['name']);
                $rideContentCreateStruct->setField('photo', new Value($rideDetail['photo']));
                $rideContentCreateStruct->setField('description', $rideDetail['description']);
                $rideContentCreateStruct->setField('starting_point', $rideDetail['starting_point']);
                $rideContentCreateStruct->setField('ending_point', $rideDetail['ending_point']);
                $rideContentCreateStruct->setField('length', $rideDetail['length']);
                $rideLocationCreateStruct = $this->locationService->newLocationCreateStruct(self::ALL_RIDES_LOCATION_ID);
                $draft = $this->contentService->createContent($rideContentCreateStruct, [$rideLocationCreateStruct]);
                $this->contentService->publishVersion( $draft->versionInfo );
            });
        }
    }

    private function rideDetails()
    {
        $file = $this->kernel->getProjectDir() . '/tests/fixtures/pexels-photo-355553.jpeg';

        return [
            [
                'name' => 'Montpellier - Nîmes',
                'photo' => [
                    'path' => $file,
                    'fileSize' => filesize( $file ),
                    'fileName' => basename( $file ),
                    'alternativeText' => 'Montpellier - Nîmes'
                ],
                'description' => <<<DOCBOOK
<?xml version="1.0" encoding="UTF-8"?>
<section xmlns="http://docbook.org/ns/docbook"
         xmlns:xlink="http://www.w3.org/1999/xlink"
         xmlns:ezxhtml="http://ez.no/xmlns/ezpublish/docbook/xhtml"
         xmlns:ezcustom="http://ez.no/xmlns/ezpublish/docbook/custom"
         version="5.0-variant ezpublish-1.0">
    <para ezxhtml:class="paraClass">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</para>
</section>
DOCBOOK,
                'starting_point' => [ 'latitude' => 43.611242, 'longitude' => 3.876734, 'address' => "Montpellier" ],
                'ending_point' => [ 'latitude' => 43.837425, 'longitude' => 4.360069, 'address' => "Nîmes" ],
                'length' => 45,
            ]
        ];
    }
}
