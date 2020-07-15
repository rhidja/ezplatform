<?php


namespace App\Tests\QueryType;


use App\QueryType\RideQueryType;
use eZ\Publish\Core\QueryType\QueryTypeRegistry;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RideQueryTypeTest extends WebTestCase
{
    /** @var QueryTypeRegistry */
    protected $registry;

    public function setUp(): void
    {
        static::bootKernel();
        $container = self::$container;
        $this->registry = $container->get('ezpublish.query_type.registry');
    }

    public function testGetName(): void
    {
        $queryType = $this->registry->getQueryType('Ride');
        //print_r($queryType->getQuery());
    }
}