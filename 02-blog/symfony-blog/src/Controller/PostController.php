<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\ValidationException;

final class PostController{

    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer; 
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, 
                                ValidatorInterface $validator){
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("/posts", methods={"POST"})   
     */
    public function create(Request $request): Response{
        //$post = new Post("Minha primeira aplicação com Symfony", "Descrição");
        
        //FAZENDO A INCLUSÃO CRIANDO O POST NA MÃO
        // $data = json_decode($request->getContent(), true);
        // $post = new Post($data['title'], $data['description']);
        // $this->entityManager->persist($post);
        // $this->entityManager->flush();

        //UTILIZANDO O DESERIALIZE
        $post = $this->serializer->deserialize($request->getContent(), Post::class, 'json');

        //Validando os dados recebidos com o validator
        $errors = $this->validator->validate($post);
        if (count($errors)) {
            throw new ValidationException($errors);
        }

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
        
        //RETORNANDO O JSON NA MÃO
        // return JsonResponse::create([
        //     'id' => $post->getId(),
        //     'title' => $post->title,
        //     'description' => $post->description,
        //     'createdAt' => $post->getCreatedAt()->format('Y-m-d'),
        // ]);

        //RETORNANDO O JSON UTILIZANDO O SERIALIZER
        if (null === $post) {
            throw new NotFoundHttpException('Post não encontrado.');
        }

        return JsonResponse::fromJsonString($this->serializer->serialize($post, 'json'));
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