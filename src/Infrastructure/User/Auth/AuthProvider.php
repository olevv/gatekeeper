<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Auth;

use App\Domain\Shared\Exception\NotFoundException;
use App\Domain\User\Exception\InvalidAccessTokenException;
use App\Domain\User\Finder\FindUserByEmail;
use App\Domain\User\ValueObject\AccessToken;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Role;
use App\Domain\User\ValueObject\Status;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class AuthProvider implements UserProviderInterface
{
    private FindUserByEmail $userFinder;

    public function __construct(FindUserByEmail $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    /**
     * @param string $email
     *
     * @return Auth|UserInterface
     *
     * @throws NotFoundException|\Exception|InvalidAccessTokenException
     */
    public function loadUserByUsername($email): UserInterface
    {
        $userView = $this->userFinder->oneByEmail(Email::fromString($email));

        $credentials = new Credentials(
            Email::fromString($userView->email),
            HashedPassword::fromHash($userView->password_hash)
        );

        $accessToken = AccessToken::createWithExpiresAt(
            $userView->access_token,
            new \DateTimeImmutable($userView->access_token_expires)
        );

        return new Auth(
            Uuid::fromString($userView->uuid),
            $credentials,
            new Role($userView->role),
            new Status($userView->status),
            $accessToken
        );
    }

    /**
     * @throws NotFoundException|InvalidAccessTokenException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class): bool
    {
        return Auth::class === $class;
    }
}
