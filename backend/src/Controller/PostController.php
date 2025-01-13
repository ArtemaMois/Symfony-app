<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
class PostController extends AbstractController
{

    public function __construct(
        private PostRepository $repository
    ) {}


    #[Route('/posts', name: 'posts')]
    public function index(): Response
    {
        $posts = $this->repository->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/posts/create', 'posts_create_form' )]
    public function create()
    {
        return $this->render('post/create.html.twig');
    }

    #[Route('/posts', 'post_store',methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager)
    {

    }

    #[Route('/posts/{post}', 'post')]
    public function show(Post $post)
    {
        return $this->render('', [
            'post' => $post
        ]);
    }
}
