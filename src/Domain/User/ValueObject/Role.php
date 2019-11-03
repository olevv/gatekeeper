<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

final class Role
{
    private const USER = 'ROLE_USER';
    private const ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $name = mb_strtoupper($name);

        Assertion::inArray($name, [
            self::USER,
            self::ADMIN,
        ]);

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
