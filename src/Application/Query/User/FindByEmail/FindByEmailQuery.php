<?php

declare(strict_types=1);

namespace App\Application\Query\User\FindByEmail;

use App\Domain\User\ValueObject\Email;

final class FindByEmailQuery
{
    public Email $email;

    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
