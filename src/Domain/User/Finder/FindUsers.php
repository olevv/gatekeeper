<?php declare(strict_types=1);

namespace App\Domain\User\Finder;

use App\Domain\User\ViewModel\UserView;

interface FindUsers
{
    /**
     * @param int $limit
     * @param int $offset
     * @return UserView[]
     */
    public function all(int $limit, int $offset): array;
}
