<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Security;

use App\Application\Command\User\ChangePassword\ChangePasswordCommand;
use App\Infrastructure\User\Auth\Auth;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserChangePasswordVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof ChangePasswordCommand;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $auth = $token->getUser();

        if (!$auth instanceof Auth) {
            return false;
        }

        return $auth->canChangePassword($subject->uuid);
    }
}
