<?php

namespace App\Service;

use App\Entity\User;
use App\FileManager\FileManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{

    public function __construct(
        private FileManager $fileManager
    ) {}
    public function updateImage(?UploadedFile $image, UserInterface $user)
    {
        if (!is_null($image)) {
            if (!is_null($user->getImage())) {
                $this->removeFile('public/uploads/avatars/' . $user->getImage());
            }
            $filePath = $this->loadImage($image);
            $user->setImage($filePath);
        }
    }

    private function loadImage(UploadedFile $uploadedFile): string
    {
        $filename = $this->fileManager->add('/public/uploads/avatars', $uploadedFile);
        return $filename;
    }

    public function removeFile(string $filename)
    {
        $this->fileManager->remove($filename);
    }

}
