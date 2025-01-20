<?php 

namespace App\Service;

use App\Entity\Dislike;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LikeService
{
    public function __construct(
        private EntityManagerInterface $entityManager        
    ) {}
    public function getUsersWhoLikedPost(User $user, Post $post)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        return $queryBuilder->select('l')->from(Like::class, 'l')
        ->where('l.post=:post AND l.user=:user')->setParameter('post', $post->getId())
        ->setParameter('user', $user->getId())->getQuery()->getResult();
    }

    public function getUsersWhoDisLikedPost(User $user, Post $post)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        return $queryBuilder->select('d')->from(Dislike::class, 'd')
        ->where('d.post=:post AND d.user=:user')->setParameter('post', $post->getId())
        ->setParameter('user', $user->getId())->getQuery()->getResult();
    }
}