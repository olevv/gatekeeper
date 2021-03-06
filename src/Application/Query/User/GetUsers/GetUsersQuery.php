<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUsers;

use App\Application\Query\Query;

final class GetUsersQuery implements Query
{
    /** @psalm-readonly */
    public int $offset;

    /** @psalm-readonly */
    public int $limit;

    public function __construct(int $offset, int $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
