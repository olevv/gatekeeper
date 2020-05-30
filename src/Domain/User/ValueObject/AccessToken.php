<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Domain\User\Exception\InvalidAccessTokenException;
use Assert\Assertion;
use Assert\AssertionFailedException;

final class AccessToken
{
    private const SALT = 'base_token';

    private const ONE_HOUR = 'PT1H';

    private string $value;

    private ?\DateTimeImmutable $expiresAt;

    /**
     * @return AccessToken
     *
     * @throws \Exception
     */
    public static function generate(): self
    {
        $self = new self();

        $self->value = hash('sha512', random_int(1, 90000) . self::SALT);
        $self->expiresAt = (new \DateTimeImmutable('now'))->add(new \DateInterval(self::ONE_HOUR));

        return $self;
    }

    public static function fromString(string $value): self
    {
        try {
            Assertion::notEmpty($value);
        } catch (AssertionFailedException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $self = new self();

        $self->value = $value;

        return $self;
    }

    /**
     * @return AccessToken
     *
     * @throws InvalidAccessTokenException
     */
    public static function createWithExpiresAt(string $value, \DateTimeImmutable $expiresAt): self
    {
        try {
            Assertion::notEmpty($value);
        } catch (AssertionFailedException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        $now = new \DateTimeImmutable('now');

        if ($now > $expiresAt) {
            throw new InvalidAccessTokenException('Token has expired');
        }

        $self = new self();

        $self->value = $value;
        $self->expiresAt = $expiresAt;

        return $self;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function expiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    private function __construct()
    {
    }
}
