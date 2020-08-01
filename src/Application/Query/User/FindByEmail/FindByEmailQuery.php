<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByEmail;

use App\Application\Query\Query;
use App\Domain\User\ValueObject\Email;

final class FindByEmailQuery implements Query
{
    /** @psalm-readonly */
    public Email $email;

    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
