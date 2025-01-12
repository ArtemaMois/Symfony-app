<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService
{
    public function setUserData(UserPasswordHasherInterface $hasher, User $user, string $plainPassword)
    {
        $this->setHashedPasswordToUser($hasher, $user, $plainPassword);
        $this->setCreatedAtToUser($user);
    }

    private function setHashedPasswordToUser(UserPasswordHasherInterface $hasher, User $user, string $plainPassword): void
    {
        $hashedPassword = $hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
    }

    private function setCreatedAtToUser(User $user)
    {
        $user->setCreated_at(new \DateTimeImmutable());
    }
}
