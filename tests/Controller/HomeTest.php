<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Home');

        $this->assertSelectorTextContains('p', 'Jakob Eriksson');
        $this->assertSelectorTextContains('p', '28');
        $this->assertSelectorTextContains('p', 'Kalmar');
    }
}
