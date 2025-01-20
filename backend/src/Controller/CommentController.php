<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentFormType;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CommentService $service
    ) {}

    #[Route('/comment', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }


    #[Route( path: 'posts/{slug}/comments', name: 'post_comments', methods: ['POST'])]
    public function store(Post $post, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->service->setFields($post, $comment);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
    }
}
