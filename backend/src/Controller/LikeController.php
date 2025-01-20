<?php

namespace App\Controller;

use App\Entity\Dislike;
use App\Entity\Like;
use App\Entity\Post;
use App\Service\LikeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LikeController extends AbstractController
{

    public function __construct(
        private Security $security,
        private SerializerInterface $serializer,
        private LikeService $service,
        private EntityManagerInterface $entityManager
    ) {}

    
    #[Route('/posts/{slug}/like', name: 'post_like')]
    public function like(Post $post, Request $request): Response
    {
        if(empty($this->service->getUsersWhoLikedPost($this->security->getUser(), $post)))
        {
            $like = new Like();
            $like->setPost($post);
            $like->setUser($this->security->getUser());
            $this->entityManager->persist($like);
            $this->entityManager->flush();
            return $this->json(['status' => 'success']);            
        }
        return $this->json(['status' => 'failed'], 400);
    }

    #[Route('/posts/{slug}/dislike', name: 'post_dislike')]
    public function dislike(Post $post, Request $request): Response
    {
        if(empty($this->service->getUsersWhoDisLikedPost($this->security->getUser(), $post)))
        {
            $like = new Dislike();
            $like->setPost($post);
            $like->setUser($this->security->getUser());
            $this->entityManager->persist($like);
            $this->entityManager->flush();
            return $this->json(['status' => 'success']);            
        }
        return $this->json(['status' => 'failed'], 400);
    }
}
