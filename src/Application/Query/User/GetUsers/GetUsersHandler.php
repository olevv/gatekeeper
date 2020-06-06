<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUsers;

use App\Domain\User\Finder\FindUsers;
use App\Domain\User\ViewModel\UserView;
use App\Infrastructure\Shared\Bus\Query\QueryHandler;

final class GetUsersHandler implements QueryHandler
{
    private FindUsers $findUsers;

    public function __construct(FindUsers $users)
    {
        $this->findUsers = $users;
    }

    /**
     * @return UserView[]
     */
    public function __invoke(GetUsersQuery $query): array
    {
        return $this->findUsers->all($query->limit, $query->offset);
    }
}
