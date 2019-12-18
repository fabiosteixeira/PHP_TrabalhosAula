<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class PostController{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/posts", methods={"POST"})   
     */
    public function create(Request $request): Response{
        //$post = new Post("Minha primeira aplicação com Symfony", "Descrição");
        
        $data = json_decode($request->getContent(), true);
        $post = new Post($data['title'], $data['description']);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new Response('OK', Response::HTTP_CREATED);
    }

    /**
     * @Route("/posts/{id}", methods={"GET"})
     */
    public function details(int $id):Response {
        /**@var Post $post */
        $post = $this->entityManager->getRepository(Post::class)->find($id);
        
        return JsonResponse::create([
            'id' => $post->getId(),
            'title' => $post->title,
            'description' => $post->description,
            'createdAt' => $post->getCreatedAt()->format('Y-m-d'),
        ]);
    }

    /**
     * @Route("/posts", methods={"GET"})
     */
    public function index():Response {
        /**@var Post[] $posts */
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        $data = [];

        foreach ($posts as $post){
            $data[] = [
                'id' => $post->getId(),
                'title' => $post->title,
                'description' => $post->description,
                'createdAt' => $post->getCreatedAt()->format('Y-m-d'),
            ];
        }
        return JsonResponse::create($data);
    }

    /**
     * @Route("/posts/{id}", methods={"PUT"})    
     */    
    public function update(Request $request, int $id): Response {
        /**@var Post $post */
        $post = $this->entityManager->getRepository(Post::class)->find($id);
        $data = json_decode($request->getContent(), true);
        
        $post->title = $data['title'];
        $post->description = $data['description'];

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new Response('Ok');
    }

    /**
     * @Route("/posts/{id}", methods={"DELETE"})        
     */      
    public function delete(int $id): Response {
        /**@var Post $post */
        $post = $this->entityManager->getRepository(Post::class)->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}