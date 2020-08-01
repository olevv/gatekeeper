<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangePassword;

use App\Application\Command\Command;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ChangePasswordCommand implements Command
{
    /** @psalm-readonly */
    public UuidInterface $uuid;

    /** @psalm-readonly */
    public HashedPassword $password;

    /**
     * @throws \App\Domain\Shared\Exception\HashedPasswordException
     */
    public function __construct(string $uuid, string $plainPassword)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->password = HashedPassword::encode($plainPassword);
    }
}
