<?php

namespace App\Tests\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Doctrine\ORM\Tools\ToolsException;
use Doctrine\ORM\Tools\SchemaTool;

class PostControllerTest extends WebTestCase {

    private EntityManagerInterface $em;
    private KernelBrowser $client;

    public function setUp(): void {
        $this->client = self::createClient();

        //cria a ferramenta para a manipulação do banco de dados
        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $tool = new SchemaTool($this->em);

        //Recupera meta informação da entidade Post
        $metadata = $this->em->getClassMetadata(Post::class);

        //Apaga a tabela associada à entidade Post
        $tool->dropSchema([$metadata]);

        try {
            //Cria a tabela associada à entidade Post
            $tool->createSchema([$metadata]);
        } catch (ToolsException $e) {
            $this->fail("Impossível criar a tabela Post: " . $e->getMessage());
        }
    }

    public function test_create_post(): void {
        // $client = static::createClient();
        $this->client->request('POST', '/posts', [], [], [], json_encode([
            'title' => 'Primeiro teste funcional',
            'description' => 'Alguma descrição'
        ]));
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function teste_delete_post():void {

        $post = new Post("Post de teste", "Teste");
        $this->em->persist($post);
        $this->em->flush();

        $this->client->request('DELETE', '/posts/1');
        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }
}