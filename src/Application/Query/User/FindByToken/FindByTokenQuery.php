<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByToken;

use App\Domain\User\ValueObject\AccessToken;

final class FindByTokenQuery
{
    public AccessToken $token;

    public function __construct(string $token)
    {
        $this->token = AccessToken::fromString($token);
    }
}
