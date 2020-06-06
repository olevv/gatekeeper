<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByToken;

use App\Domain\Shared\Exception\NotFoundException;
use App\Domain\User\Exception\InvalidAccessTokenException;
use App\Domain\User\Finder\FindUserByAccessToken;
use App\Domain\User\ViewModel\UserView;
use App\Infrastructure\Shared\Bus\Query\QueryHandler;

final class FindByTokenHandler implements QueryHandler
{
    private FindUserByAccessToken $userFinder;

    public function __construct(FindUserByAccessToken $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    public function __invoke(FindByTokenQuery $query): UserView
    {
        try {
            return $this->userFinder->oneByToken($query->token);
        } catch (NotFoundException $e) {
            throw new InvalidAccessTokenException();
        }
    }
}
