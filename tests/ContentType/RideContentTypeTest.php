<?php


namespace App\Tests\ContentType;


use eZ\Publish\API\Repository\Repository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RideContentTypeTest extends WebTestCase
{
    /**
     * @var Repository
     */
    protected $repository;

    public function setUp(): void
    {
        static::bootKernel();
        $container = self::$container;
        $this->repository  = $container->get(Repository::class);
    }

    /**
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testGetName(): void
    {
        $rideContentType = $this->repository->getContentTypeService()->loadContentTypeByIdentifier('ride');
        $this->assertSame('Ride', $rideContentType->getName());
        $this->assertSame(6, $rideContentType->fieldDefinitions->count());

        $photoFieldDefinition = $rideContentType->fieldDefinitions->get('name');
        $this->assertSame('ezstring', $photoFieldDefinition->fieldTypeIdentifier);
        $this->assertTrue($photoFieldDefinition->isRequired);
        $this->assertTrue($photoFieldDefinition->isSearchable);
        $this->assertTrue($photoFieldDefinition->isTranslatable);

        $nameFieldDefinition = $rideContentType->fieldDefinitions->get('photo');
        print_r($nameFieldDefinition);
        $this->assertSame('ezimage', $nameFieldDefinition->fieldTypeIdentifier);
        $this->assertFalse($nameFieldDefinition->isRequired);
        $this->assertTrue($photoFieldDefinition->isTranslatable);

        $lengthFieldDefinition = $rideContentType->fieldDefinitions->get('description');
        $this->assertSame('ezrichtext', $lengthFieldDefinition->fieldTypeIdentifier);
        $this->assertTrue($lengthFieldDefinition->isRequired);
        $this->assertTrue($lengthFieldDefinition->isSearchable);
        $this->assertTrue($photoFieldDefinition->isTranslatable);

        $lengthFieldDefinition = $rideContentType->fieldDefinitions->get('starting_point');
        $this->assertSame('ezgmaplocation', $lengthFieldDefinition->fieldTypeIdentifier);
        $this->assertTrue($lengthFieldDefinition->isRequired);
        $this->assertTrue($lengthFieldDefinition->isSearchable);
        $this->assertTrue($photoFieldDefinition->isTranslatable);

        $lengthFieldDefinition = $rideContentType->fieldDefinitions->get('ending_point');
        $this->assertSame('ezgmaplocation', $lengthFieldDefinition->fieldTypeIdentifier);
        $this->assertTrue($lengthFieldDefinition->isRequired);
        $this->assertTrue($lengthFieldDefinition->isSearchable);
        $this->assertTrue($photoFieldDefinition->isTranslatable);

        $nameFieldDefinition = $rideContentType->fieldDefinitions->get('length');
        $this->assertSame('ezinteger', $nameFieldDefinition->fieldTypeIdentifier);
        $this->assertTrue($nameFieldDefinition->isRequired);
        $this->assertTrue($nameFieldDefinition->isSearchable);
        $this->assertTrue($photoFieldDefinition->isTranslatable);
    }
}