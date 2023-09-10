<?php

// src/EventListener/VerifyEmailListener.php
namespace App\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class VerifyEmailListener
{
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user->isIsVerified()) {
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas vérifié.');
        }
    }
}
