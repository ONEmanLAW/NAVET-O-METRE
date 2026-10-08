<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminUserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
    }

    // Same error as a wrong password, so the form never reveals which accounts exist or are admins.
    public function checkPostAuth(UserInterface $user, ?TokenInterface $token = null): void
    {
        if (!in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            throw new BadCredentialsException();
        }
    }
}
