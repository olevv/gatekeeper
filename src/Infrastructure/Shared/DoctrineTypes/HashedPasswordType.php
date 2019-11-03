<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\DoctrineTypes;

use App\Domain\User\ValueObject\Auth\HashedPassword;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class HashedPasswordType extends StringType
{
    public const NAME = 'password_hash';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof HashedPassword ? $value->toString() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? HashedPassword::fromHash((string) $value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
