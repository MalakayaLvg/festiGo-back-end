<?php
// src/EventListener/LoginListener.php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

#[AsEventListener(event: CheckPassportEvent::class)]
class LoginListener
{
    public function __invoke(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();
        $user = $passport->getUser();

        if ($user instanceof User && !$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas encore vérifié. Veuillez vérifier votre email.');
        }
    }
}
