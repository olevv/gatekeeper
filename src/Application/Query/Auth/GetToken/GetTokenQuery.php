<?php

declare(strict_types=1);

namespace App\Application\Query\Auth\GetToken;

use App\Application\Query\Query;
use App\Domain\User\ValueObject\Email;

final class GetTokenQuery implements Query
{
    /** @psalm-readonly */
    public Email $email;

    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
