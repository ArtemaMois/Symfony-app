<?php

namespace App\Service;

use App\Entity\Post;
use App\FileManager\FileManager;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostService
{

    public function __construct(
        private Security $security,
        private SluggerInterface $slugger,
        private FileManager $fileManager,
    ) {}
    public function setDefaultFields(Post $post)
    {
        $post->setSlug($this->setSlug($post->getTitle()));
        $post->setCreatedAt($this->setCreatedAt());
        $this->setUser($post);
    }

    private function setSlug(string $title)
    {
        return $this->slugger->slug($title);
    }

    private function setCreatedAt(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    private function setUser(Post $post)
    {
        $post->setUser($this->security->getUser());
    }

    public function loadFile(?UploadedFile $file): ?string
    {
        if (!is_null($file)) {
            $filename = $this->fileManager->add('/public/uploads/posts', $file);
            return $filename;
        }
        return null;
    }

    public function updateFile(Post $post, ?UploadedFile $file)
    {
        if(!is_null($file))
        {
            $this->removeFile('public/uploads/posts/' . $post->getImage());
            $filename =  $this->loadFile($file);
            $post->setImage($filename);
        }
    }

    public function removeFile(string $filename)
    {
        $this->fileManager->remove($filename);
    }


}
