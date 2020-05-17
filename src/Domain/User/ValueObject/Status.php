<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

final class Status
{
    private const ACTIVE = 'ACTIVE';
    private const BLOCKED = 'BLOCKED';

    private string $name;

    public function __construct(string $name)
    {
        $name = strtoupper($name);

        Assertion::inArray($name, [
            self::ACTIVE,
            self::BLOCKED,
        ]);

        $this->name = $name;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function blocked(): self
    {
        return new self(self::BLOCKED);
    }

    public function equals(Status $status): bool
    {
        return $this->name === $status->toString();
    }

    public function toString(): string
    {
        return $this->name;
    }
}
