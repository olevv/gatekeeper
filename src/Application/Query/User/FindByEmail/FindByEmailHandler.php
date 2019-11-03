<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByEmail;

use App\Application\Query\QueryHandler;
use App\Domain\User\Finder\FindUserByEmail;
use App\Domain\User\ViewModel\UserView;

final class FindByEmailHandler implements QueryHandler
{
    /**
     * @var FindUserByEmail
     */
    private $userFinder;

    public function __construct(FindUserByEmail $userFinder)
    {
        $this->userFinder = $userFinder;
    }

    /**
     * @param FindByEmailQuery $query
     * @return UserView
     * @throws \App\Domain\Shared\Exception\NotFoundException
     */
    public function __invoke(FindByEmailQuery $query): UserView
    {
        return $this->userFinder->oneByEmail($query->email);
    }
}
