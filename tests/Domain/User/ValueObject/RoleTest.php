<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Role;
use PHPUnit\Framework\TestCase;

final class RoleTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function invalid_role_should_throw_an_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Role('super_user');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function valid_role_should_be_able_to_convert_to_string(): void
    {
        $role = Role::user();

        self::assertTrue($role->equals(Role::user()));
    }
}
