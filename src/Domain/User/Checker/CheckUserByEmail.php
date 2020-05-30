<?php

declare(strict_types=1);

namespace App\Domain\User\Checker;

use App\Domain\User\ValueObject\Email;

interface CheckUserByEmail
{
    public function existsEmail(Email $email): bool;
}
