<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\SignIn;

use App\Application\Command\User\SignIn\SignInCommand;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Tests\Application\ApplicationTestCase;

final class SignInTest extends ApplicationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createUser('example@test.ru', 'test1234');
    }

    /**
     * @test
     *
     * @group integration
     *
     * @dataProvider invalidCredentialsExamples
     *
     * @throws \InvalidArgumentException
     */
    public function user_sign_up_with_invalid_credentials_should_throw_exception(string $email, string $password): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $command = new SignInCommand($email, $password);

        $this->handle($command);
    }

    public function invalidCredentialsExamples(): array
    {
        return [
            [
                'email' => 'example1@test.ru',
                'pass' => 'test1234',
            ],
            [
                'email' => 'example@test.ru',
                'pass' => 'test12',
            ],
        ];
    }
}
