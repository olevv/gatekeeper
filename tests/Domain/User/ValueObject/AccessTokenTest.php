<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\ValueObject;

use App\Domain\User\Exception\InvalidAccessTokenException;
use App\Domain\User\ValueObject\AccessToken;
use PHPUnit\Framework\TestCase;

final class AccessTokenTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function invalid_access_token_should_throw_an_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        AccessToken::fromString('');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function invalid_access_token_when_time_has_expired_should_throw_an_exception(): void
    {
        $this->expectException(InvalidAccessTokenException::class);

        AccessToken::createWithExpiresAt(
            '53920d23cf04a69fefe4bb85eae577d4c72e0c51',
            new \DateTimeImmutable('2019-10-10')
        );
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function valid_access_token_should_be_able_to_convert_to_string(): void
    {
        $token = AccessToken::fromString('53920d23cf04a69fefe4bb85eae577d4c72e0c51');

        self::assertSame('53920d23cf04a69fefe4bb85eae577d4c72e0c51', $token->toString());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function valid_access_token_with_correct_period_should_be_able_to_convert_to_string(): void
    {
        $expiresAt = (new \DateTimeImmutable('now'))->add(new \DateInterval('PT1H'));
        $token = AccessToken::createWithExpiresAt(
            '53920d23cf04a69fefe4bb85eae577d4c72e0c51',
            $expiresAt
        );

        self::assertSame('53920d23cf04a69fefe4bb85eae577d4c72e0c51', $token->toString());
        self::assertNotNull($token->expiresAt());
        self::assertTrue($token->expiresAt() > new \DateTimeImmutable('now'));
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     */
    public function valid_access_token_generated_token_with_correct_period(): void
    {
        $token = AccessToken::generate();

        self::assertNotSame($token->toString(), '');
        self::assertNotNull($token->expiresAt());
        self::assertTrue($token->expiresAt() > new \DateTimeImmutable('now'));
    }
}
