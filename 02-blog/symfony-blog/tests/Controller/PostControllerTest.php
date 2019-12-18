<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends WebTestCase {
    public function test_create_post(): void {
        $client = static::createClient();
        $client->request('POST', '/posts', [], [], [], json_encode([
            'title' => 'Primeiro teste funcional',
            'description' => 'Alguma descrição'
        ]));
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}