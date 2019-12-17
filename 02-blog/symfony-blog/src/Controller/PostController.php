<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PostController{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/posts", methods={"Post"})
     */

    public function create(): Response{
        $post = new Post("Minha primeira aplicação com Symfony", "Descrição");
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new Response('OK', Response::HTTP_CREATED);
    }
}