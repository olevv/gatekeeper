<?php

declare(strict_types=1);

namespace App\Application\Query\Auth\GetToken;

use App\Domain\User\Finder\FindUserByEmail;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\ValueObject\AccessToken;
use App\Infrastructure\Shared\Bus\Query\QueryHandler;
use App\Infrastructure\Shared\Flusher\Flusher;
use Ramsey\Uuid\Uuid;

final class GetTokenHandler implements QueryHandler
{
    private FindUserByEmail $userFinder;

    private UserRepository $userStore;

    private Flusher $flusher;

    public function __construct(FindUserByEmail $userFinder, UserRepository $userStore, Flusher $flusher)
    {
        $this->userFinder = $userFinder;
        $this->userStore = $userStore;
        $this->flusher = $flusher;
    }

    public function __invoke(GetTokenQuery $query): string
    {
        $userView = $this->userFinder->oneByEmail($query->email);

        $user = $this->userStore->get(Uuid::fromString($userView->uuid));

        $token = AccessToken::generate();

        $user->updateAccessToken($token);

        $this->flusher->flush();

        return $token->toString();
    }
}
