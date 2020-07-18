<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\SignUp;

use App\Application\Command\User\SignUp\SignUpCommand;
use App\Domain\User\Exception\EmailAlreadyExistException;
use App\Tests\Application\ApplicationTestCase;
use Ramsey\Uuid\Uuid;

final class SignUpHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function user_sign_up_given_not_exists_should_registered_successfully(): void
    {
        $uuid = Uuid::uuid4();

        $command = new SignUpCommand(
            $uuid->toString(),
            'new_user@example.com',
            'example'
        );

        $this->handle($command);

        $user = $this->getUser($uuid);

        self::assertEquals($uuid->toString(), $user->uuid());
    }

    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function user_sign_up_given_exists_should_throw_exception(): void
    {
        $this->createUser('user@example.com', '1234567');

        $this->expectException(EmailAlreadyExistException::class);

        $uuid = Uuid::uuid4();

        $command = new SignUpCommand(
            $uuid->toString(),
            'user@example.com',
            '1234567'
        );

        $this->handle($command);
    }
}
