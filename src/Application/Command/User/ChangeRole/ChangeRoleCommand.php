<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeRole;

use App\Domain\User\ValueObject\Role;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ChangeRoleCommand
{
    public Role $role;
    public UuidInterface $uuid;

    public function __construct(string $uuid, string $role)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->role = new Role($role);
    }
}
