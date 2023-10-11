<?php

// src/Security/CustomUserCheckerListener.php

namespace App\Security;

use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PreAuthenticatedUserBadge;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\EventListener\UserCheckerListener;

class CustomUserCheckerListener extends UserCheckerListener
{
    public function __construct(UserCheckerInterface $userChecker)
    {
        parent::__construct($userChecker);
    }

    public function preCheckCredentials(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();

        $password = $passport->getBadge('Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials');
        $password->setPassword($password->getPassword());
        parent::preCheckCredentials($event);
    }


}
