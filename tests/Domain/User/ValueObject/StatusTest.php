<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Status;
use PHPUnit\Framework\TestCase;

final class StatusTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function invalid_status_should_throw_an_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Status('not_found');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function valid_status_should_be_able_to_convert_to_string(): void
    {
        $status = Status::active();

        self::assertTrue($status->equals(Status::active()));
    }
}
