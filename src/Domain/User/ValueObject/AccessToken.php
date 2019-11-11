<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Domain\User\Exception\InvalidAccessTokenException;
use Assert\Assertion;

final class AccessToken
{
    private const SALT = 'base_token';
    private const ONE_HOUR = 'PT1H';

    /** @var string */
    private $value;

    /** @var \DateTimeImmutable|null */
    private $expires_at;

    /**
     * @return AccessToken
     * @throws \Exception
     */
    public static function generate(): self
    {
        $self = new self();

        $self->value = sha1(random_int(1, 90000).self::SALT);
        $self->expires_at = (new \DateTimeImmutable('now'))->add(new \DateInterval(self::ONE_HOUR));

        return $self;
    }

    /**
     * @param string $value
     *
     * @return AccessToken
     * @throws \Assert\AssertionFailedException
     */
    public static function fromString(string $value): self
    {
        Assertion::notEmpty($value);

        $self = new self();

        $self->value = $value;

        return $self;
    }

    /**
     * @param string $value
     *
     * @param \DateTimeImmutable $expiresAt
     * @return AccessToken
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public static function createWithExpiresAt(string $value, \DateTimeImmutable $expiresAt): self
    {
        Assertion::notEmpty($value);

        $now = new \DateTimeImmutable('now');

        if ($now > $expiresAt) {
            throw new InvalidAccessTokenException('Token has expired');
        }

        $self = new self();

        $self->value = $value;
        $self->expires_at = $expiresAt;

        return $self;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function expiresAt(): ?\DateTimeImmutable
    {
        return $this->expires_at;
    }

    private function __construct()
    {
    }
}
