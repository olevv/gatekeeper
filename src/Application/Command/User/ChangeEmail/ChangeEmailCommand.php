<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeEmail;

use App\Domain\User\ValueObject\Email;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ChangeEmailCommand
{
    public UuidInterface $uuid;
    public Email $email;

    public function __construct(string $uuid, string $email)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->email = Email::fromString($email);
    }
}
