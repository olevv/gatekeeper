<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangePassword;

use App\Domain\User\ValueObject\Auth\HashedPassword;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ChangePasswordCommand
{
    /**
     * @var UuidInterface
     */
    public $uuid;
    /**
     * @var HashedPassword
     */
    public $password;

    /**
     * @param string $uuid
     * @param string $plainPassword
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $uuid, string $plainPassword)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->password = HashedPassword::encode($plainPassword);
    }
}
