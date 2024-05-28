<?php

namespace App\Tests\Controller\JsonApi;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JsonApiControllerTest extends WebTestCase
{
    public function testJsonRoutes()
    {
        $client = static::createClient();
        $client->request('GET', '/api');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('api/qoute', $client->getResponse()->getContent());
    }

    public function testJsonQoute()
    {
        $client = static::createClient();
        $client->request('GET', '/api/qoute');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testJsonDeck()
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testJsonDeckShuffle()
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('deckOfCards', $client->getResponse()->getContent());
    }

    public function testJsonDeckDrawNumber()
    {
        $client = static::createClient();
        $client->request('POST', '/api/deck/shuffle');
        $client->request('POST', '/api/deck/draw/5');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $this->assertStringContainsString('drawnCards', $client->getResponse()->getContent());
    }

    public function testJsonBooks()
    {
        $client = static::createClient();
        $client->request('GET', '/api/library/books');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }
}
