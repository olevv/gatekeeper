<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject\Auth;

use Assert\Assertion;

final class HashedPassword
{
    /** @var string */
    private $hashedPassword;

    private const COST = 12;

    /**
     * @param string $plainPassword
     * @return HashedPassword
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function encode(string $plainPassword): self
    {
        $self = new self();

        Assertion::minLength($plainPassword, 6, 'Min 6 characters password');

        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => self::COST]);

        if (\is_bool($hashedPassword)) {
            throw new \RuntimeException('Server error hashing password');
        }

        $self->hashedPassword = $hashedPassword;

        return $self;
    }

    public static function fromHash(string $hashedPassword): self
    {
        $pass = new self();

        $pass->hashedPassword = $hashedPassword;

        return $pass;
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
