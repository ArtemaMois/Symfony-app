<?php 

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Post;
use Symfony\Bundle\SecurityBundle\Security;

class CommentService
{
    public function __construct(
        private Security $security
    ) {}

    public function setFields(Post $post, Comment $comment)
    {
        $this->setCreatedAt($comment);
        $this->setUser($comment);
        $this->setPost($post, $comment);
    }

    private function setCreatedAt(Comment $comment)
    {
        $comment->setCreatedAt(new \DateTimeImmutable());
    }

    private function setUser(Comment $comment)
    {
        $comment->setUser($this->security->getUser());
    }

    private function setPost(Post $post, Comment $comment)
    {
        $comment->setPost($post);
    }
}