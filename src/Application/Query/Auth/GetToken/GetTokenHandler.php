<?php

declare(strict_types=1);

namespace App\Application\Query\Auth\GetToken;

use App\Application\Query\QueryHandler;
use App\Domain\User\Finder\FindUserByEmail;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\ValueObject\AccessToken;
use App\Infrastructure\Shared\Flusher\Flusher;
use Ramsey\Uuid\Uuid;

final class GetTokenHandler implements QueryHandler
{
    private FindUserByEmail $userFinder;

    private UserRepository $userRepository;

    private Flusher $flusher;

    public function __construct(FindUserByEmail $userFinder, UserRepository $userRepository, Flusher $flusher)
    {
        $this->userFinder = $userFinder;
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    public function __invoke(GetTokenQuery $query): string
    {
        $userView = $this->userFinder->oneByEmail($query->email);

        $user = $this->userRepository->get(Uuid::fromString($userView->uuid));

        $token = AccessToken::generate();

        $user->updateAccessToken($token);

        $this->flusher->flush($user);

        return $token->toString();
    }
}
