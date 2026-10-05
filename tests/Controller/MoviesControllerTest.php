<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MoviesControllerTest extends WebTestCase
{
    
    public function testWithoutQueryParameter() {
        $client = static::createClient();
        $client->request('Get', '/movies');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Hey this is my default value!']),
            $client->getResponse()->getContent()
        );
    }

    public function testWithMultipleQueryParameters() {
        $client = static::createClient();
        $client->request('Get', '/movies?message=Hello&other=test');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        self::assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Hello']),
            $client->getResponse()->getContent()
        );
    }
}
