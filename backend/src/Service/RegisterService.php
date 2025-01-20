<?php

namespace App\Service;

use App\Entity\User;
use Gedmo\Sluggable\Sluggable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegisterService
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private SluggerInterface $slugger
    ) {}
    public function setUserData(User $user, string $plainPassword)
    {
        $this->setHashedPasswordToUser( $user, $plainPassword);
        $this->setCreatedAtToUser($user);
        $this->setUserSlug($user);
    }

    private function setHashedPasswordToUser(User $user, string $plainPassword): void
    {
        $hashedPassword = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
    }

    private function setCreatedAtToUser(User $user)
    {
        $user->setCreatedAt(new \DateTimeImmutable());
    }

    public function setUserSlug(User $user)
    {
        $slug = $this->slugger->slug($user->getName());
        $user->setSlug($slug);
    }
}
