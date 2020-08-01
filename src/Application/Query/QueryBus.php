<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Query;

interface QueryBus
{
    /**
     * @return mixed
     */
    public function ask(Query $query);
}
