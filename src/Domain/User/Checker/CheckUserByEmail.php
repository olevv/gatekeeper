<?php

declare(strict_types=1);

namespace App\Domain\User\Checker;

use App\Domain\User\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface CheckUserByEmail
{
    public function existsEmail(Email $email): bool;
}
