<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\ChangeEmail;

use App\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use App\Application\Command\User\SignUp\SignUpCommand;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\User\Finder\PostgresUserFinder;
use App\Tests\Application\ApplicationTestCase;
use Ramsey\Uuid\Uuid;

final class ChangeEmailHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function update_user_email_given_command_should_changed_successfully(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $command = new SignUpCommand($uuid, 'test@example.com', '123456');

        $this->exec($command);

        $email = 'new_test@example.com';

        $command = new ChangeEmailCommand($uuid, $email);

        $this->exec($command);

        $userFinder = self::$container->get(PostgresUserFinder::class);

        $userView = $userFinder->oneByEmail(Email::fromString($email));

        self::assertSame($userView->email, $email);
    }
}
