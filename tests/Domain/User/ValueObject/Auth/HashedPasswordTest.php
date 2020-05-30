<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\ValueObject\Auth;

use App\Domain\User\ValueObject\Auth\HashedPassword;
use PHPUnit\Framework\TestCase;

final class HashedPasswordTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function encoded_password_should_be_validated(): void
    {
        $password = HashedPassword::encode('test123');

        self::assertTrue($password->match('test123'));
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function min_6_password_length(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        HashedPassword::encode('123');
    }

    /**
     * @test
     *
     * @group unit
     */
    public function from_hash_password_should_still_valid(): void
    {
        $hashedPassword = (HashedPassword::encode('password12'))->toString();

        $password = HashedPassword::fromHash($hashedPassword);

        self::assertTrue($password->match('password12'));
    }
}
