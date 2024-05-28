<?php

// tests/Controller/HomeControllerTest.php

namespace App\Tests\Controller;

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

        // Check that the response contains the expected data
        $this->assertStringContainsString('Jakob Eriksson', $client->getResponse()->getContent());
        $this->assertStringContainsString('28-year-old', $client->getResponse()->getContent());
        $this->assertStringContainsString('living in Kalmar', $client->getResponse()->getContent());

        // Check specific HTML elements for expected text
        $this->assertSelectorTextContains('.home-intro h3', 'Hi there!');
        $this->assertSelectorTextContains('.home-intro p', "I'm Jakob Eriksson, a 28-year-old sports enthusiast living in Kalmar.");
    }
}
