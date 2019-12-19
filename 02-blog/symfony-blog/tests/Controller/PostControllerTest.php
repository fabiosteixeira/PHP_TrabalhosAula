<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\KernelBrowser;

class PostControllerTest extends WebTestCase {

    private EntityManagerInterface $em;
    private KernelBrowser $client;

    public function setUp(): void {
        echo('Teste de setup');
    }

    public function test_create_post(): void {
        $client = static::createClient();
        $client->request('POST', '/posts', [], [], [], json_encode([
            'title' => 'Primeiro teste funcional',
            'description' => 'Alguma descrição'
        ]));
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    // public function teste_delete_post():void {
    //     $client = static::createClient();
    //     $client->request('DELETE', '/posts/3');
    //     $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    // }
}