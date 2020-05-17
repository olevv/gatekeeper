<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class Email
{
    private string $email;

    /**
     * @param string $email
     * @return Email
     *
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $email): self
    {
        try {
            Assertion::email($email, 'Not a valid email');
        } catch (AssertionFailedException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $mail = new self();

        $mail->email = $email;

        return $mail;
    }

    private function __construct()
    {
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function equals(Email $email): bool
    {
        return $this->email === $email->toString();
    }
}
