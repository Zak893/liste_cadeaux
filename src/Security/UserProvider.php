<?php

// src/Security/UserProvider.php
namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        dd('rr');
        // Chargez l'utilisateur depuis votre source de données (par exemple, la base de données)
        $user = $this->userRepository->findOneBy(['email' => $username]);
        // Vérifiez si l'utilisateur existe et si son compte est vérifié
        if (!$user || !$user->isIsVerified()) {
            throw new CustomUserMessageAccountStatusException('User not found or not verified');
        }

        return $user;
    }

    // ...

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }

    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // TODO: Implement loadUserByIdentifier() method.
    }
}
