<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $repository,
        private EntityManagerInterface $entityManager,
        private PostService $service,
        private Security $security
    ) {}

    #[Route(path: '/posts/new', name: 'post_store', methods: ['POST', 'GET'])]
    public function store(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->setDefaultFields($post);
            $fileName = $this->service->loadFile($form->get('image')->getData());
            $post->setImage($fileName);
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->addFlash('success', 'The post data has been stored successfully');
            return $this->redirectToRoute('posts_self');
        }
        return $this->render('post/create.html.twig', [
            'postForm' => $form
        ]);
    }

    #[Route(path: '/posts', name: 'posts')]
    public function index(Request $request): Response
    {
        $posts = $this->service->getPosts($request->get('title'));

        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route(path: '/posts/self', name: 'posts_self')]
    public function myPosts()
    {
        return $this->render('post/my_posts.html.twig', [
            'posts' => $this->security->getUser()->getPosts()
        ]);
    }

    #[Route('/posts/{slug}', 'post_show')]
    public function show(Post $post)
    {
        return $this->render('post/show_post.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('posts/{slug}/update', 'post_update', methods: ['GET', 'PATCH', 'POST'])]
    public function update(Post $post, Request $request)
    {
        if($post->getUser()->getUserIdentifier() !== $this->security->getUser()->getUserIdentifier())
        {
            return  is_null($request->headers->get('referer')) ? $this->redirectToRoute('posts') : $this->redirect($request->headers->get('referer'));
        }
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->updateFile($post, $form->get('image')->getData());
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->addFlash('success', 'The post data has been updated successfully');
            return $this->redirectToRoute('posts_self');
        }
        return $this->render('post/update.html.twig', [
            'postForm' => $form,
            'post' => $post
        ]);
    }

    #[Route('/posts/{slug}/delete', name:'post_delete', methods: ['POST'])]
    public function delete(Post $post, Request $request)
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();
        $this->addFlash('success', '');
        return $this->redirectToRoute('posts_self');
    }

    #[Route(path: '/posts?category={slug}', name:'posts_by_category')]
    public function postsByCategory(Category $category, Request $request)
    {
        return $this->render('post/posts.html.twig', [
            'posts' => $category->getPosts(),
            'category' => $category
        ]);
    }
}
