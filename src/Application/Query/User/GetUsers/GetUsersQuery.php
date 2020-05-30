<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUsers;

final class GetUsersQuery
{
    public int $offset;

    public int $limit;

    public function __construct(int $offset, int $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
