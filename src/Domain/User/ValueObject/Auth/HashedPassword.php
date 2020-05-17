<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject\Auth;

use App\Domain\Shared\Exception\HashedPasswordException;
use Assert\Assertion;
use Assert\AssertionFailedException;
use RuntimeException;

final class HashedPassword
{
    private string $hashedPassword;

    private const COST = 12;

    /**
     * @param string $plainPassword
     * @return HashedPassword
     *
     * @throws HashedPasswordException
     * @throws \InvalidArgumentException
     */
    public static function encode(string $plainPassword): self
    {
        $self = new self();

        try {
            Assertion::minLength($plainPassword, 6, 'Min 6 characters password');
        } catch (AssertionFailedException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => self::COST]);

        if (\is_bool($hashedPassword) || $hashedPassword === null) {
            throw new HashedPasswordException('Server error hashing password');
        }

        $self->hashedPassword = (string)$hashedPassword;

        return $self;
    }

    public static function fromHash(string $hashedPassword): self
    {
        $self = new self();

        $self->hashedPassword = $hashedPassword;

        return $self;
    }

    public function match(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }


    public function toString(): string
    {
        return $this->hashedPassword;
    }

    private function __construct()
    {
    }
}
