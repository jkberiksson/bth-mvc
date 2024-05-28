<?php

namespace App\Tests\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageIsSuccessful()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Home');
    }

    public function testHomePageContent()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertStringContainsString('Jakob Eriksson', $client->getResponse()->getContent());
        $this->assertStringContainsString('28-year-old', $client->getResponse()->getContent());
        $this->assertStringContainsString('living in Kalmar', $client->getResponse()->getContent());
    }
}
