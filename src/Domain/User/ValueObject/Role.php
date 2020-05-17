<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class Role
{
    private const USER = 'ROLE_USER';
    private const ADMIN = 'ROLE_ADMIN';

    private string $name;

    public function __construct(string $name)
    {
        $name = strtoupper($name);

        try {
            Assertion::inArray(
                $name,
                [
                    self::USER,
                    self::ADMIN,
                ]
            );
        } catch (AssertionFailedException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $this->name = $name;
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function equals(Role $role): bool
    {
        return $this->name === $role->toString();
    }
}
