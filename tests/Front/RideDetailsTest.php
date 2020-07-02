<?php


namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RideDetailsTest extends WebTestCase
{
    public function testSignePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/all-rides/montpellier-nimes');
        $this->assertStringContainsString('<h3 class="banner-header">Discover new rides! Go Bike!</h3>', $client->getResponse()->getContent());
        $this->assertSelectorTextContains('h3.center.bottom-plus.new-header','Montpellier - Nîmes');
        // Tester si les deux carte exisite
        $this->assertCount(2, $crawler->filter('div.ezgmaplocation-field'));
        $this->assertStringContainsString('<h4 class="underscore">Starting point</h4>', $client->getResponse()->getContent());
        $this->assertStringContainsString('<h4 class="underscore">Ending point</h4>', $client->getResponse()->getContent());
        $this->assertStringContainsString('<h4 class="underscore">Description</h4>', $client->getResponse()->getContent());
        $this->assertResponseIsSuccessful();
    }
}