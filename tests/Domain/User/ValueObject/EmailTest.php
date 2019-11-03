<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\ValueObject;

use App\Domain\User\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function invalid_email_should_throw_an_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Email::fromString('test@testru');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function valid_email_should_be_able_to_convert_to_string(): void
    {
        $email = Email::fromString('example@example.com');

        self::assertSame('example@example.com', $email->toString());
    }
}
