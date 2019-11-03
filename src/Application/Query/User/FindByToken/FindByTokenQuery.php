<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByToken;

use App\Domain\User\ValueObject\AccessToken;

final class FindByTokenQuery
{
    /** @var AccessToken */
    public $token;

    /**
     * @param string $token
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $token)
    {
        $this->token = AccessToken::fromString($token);
    }
}
