<?php

declare(strict_types=1);

namespace App\Application\Query\Auth\GetToken;

use App\Domain\User\ValueObject\Email;

final class GetTokenQuery
{
    public Email $email;

    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
