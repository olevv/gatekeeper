<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUsers;

use App\Application\Query\QueryHandler;
use App\Domain\User\Finder\FindUsers;
use App\Domain\User\ViewModel\UserView;

final class GetUsersHandler implements QueryHandler
{
    private FindUsers $findUsers;

    public function __construct(FindUsers $users)
    {
        $this->findUsers = $users;
    }

    /**
     * @param GetUsersQuery $query
     *
     * @return UserView[]
     */
    public function __invoke(GetUsersQuery $query): array
    {
        return $this->findUsers->all($query->limit, $query->offset);
    }
}
