<?php

declare(strict_types=1);

namespace App\Tests\Domain\User;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Exception\EmailAlreadyExistException;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Specification\UniqueEmailSpecification;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Role;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class UserTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_valid_email_it_should_create_new_user(): void
    {
        $email = 'test@test.com';

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(Email::fromString($email), HashedPassword::encode('test123')),
            $this->uniqueEmailSpecification()
        );

        self::assertNotNull($user->uuid());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_registered_email_it_should_throw_an_email_already_exists_exception(): void
    {
        $this->expectException(EmailAlreadyExistException::class);

        $email = 'test@test.com';

        User::create(
            Uuid::uuid4(),
            new Credentials(Email::fromString($email), HashedPassword::encode('test123')),
            $this->uniqueEmailSpecification(true)
        );
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_changed_email_it_should_throw_an_email_already_exists_exception(): void
    {
        $this->expectException(EmailAlreadyExistException::class);

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(Email::fromString('test@test.com'), HashedPassword::encode('test123')),
            $this->uniqueEmailSpecification()
        );

        $user->changeEmail(Email::fromString('test@test.com'), $this->uniqueEmailSpecification(true));
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_signed_it_should_throw_an_invalid_credentials_exception(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(Email::fromString('test@test.com'), HashedPassword::encode('test123')),
            $this->uniqueEmailSpecification()
        );

        $user->signIn('test12345');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_blocked_should_that_user_already_blocked_throw_an_domain_exception(): void
    {
        $this->expectException(DomainException::class);

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(Email::fromString('test@test.com'), HashedPassword::encode('test123')),
            $this->uniqueEmailSpecification()
        );

        $user->block();
        $user->block();
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_unblocked_should_that_user_already_active_throw_an_domain_exception(): void
    {
        $this->expectException(DomainException::class);

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(Email::fromString('test@test.com'), HashedPassword::encode('test123')),
            $this->uniqueEmailSpecification()
        );

        $user->unblock();
        $user->unblock();
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function given_changed_role_should_that_role_already_same_throw_an_domain_exception(): void
    {
        $this->expectException(DomainException::class);

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(Email::fromString('test@test.com'), HashedPassword::encode('test123')),
            $this->uniqueEmailSpecification()
        );

        $user->changeRole(Role::user());
    }

    private function uniqueEmailSpecification(bool $isUniqueException = false): UniqueEmailSpecification
    {
        $uniqueEmail = new class() implements UniqueEmailSpecification {
            public $isUniqueException;

            /**
             * @throws EmailAlreadyExistException
             */
            public function isUnique(Email $email): bool
            {
                if ($this->isUniqueException) {
                    throw new EmailAlreadyExistException();
                }

                return true;
            }
        };

        $uniqueEmail->isUniqueException = $isUniqueException;

        return $uniqueEmail;
    }
}
