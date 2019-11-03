<?php declare(strict_types=1);

namespace App\Infrastructure\User\Security;

use App\Application\Command\User\Block\BlockCommand;
use App\Infrastructure\User\Auth\Auth;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserBlockVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof BlockCommand;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var Auth $auth */
        $auth = $token->getUser();

        return $auth->canBlockUser();
    }
}
