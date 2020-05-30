<?php

declare(strict_types=1);

namespace App\Domain\User\Finder;

use App\Domain\User\ViewModel\UserView;

interface FindUsers
{
    /**
     * @return UserView[]
     */
    public function all(int $limit, int $offset): array;
}
