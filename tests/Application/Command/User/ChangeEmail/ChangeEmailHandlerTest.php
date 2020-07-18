<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\ChangeEmail;

use App\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use App\Tests\Application\ApplicationTestCase;

final class ChangeEmailHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function update_user_email_given_command_should_changed_successfully(): void
    {
        $uuid = $this->createUser('test@example.com', '123456');

        $email = 'new_test@example.com';

        $command = new ChangeEmailCommand($uuid->toString(), $email);

        $this->handle($command);

        $userView = $this->getUserViewByEmail($email);

        self::assertSame($userView->email, $email);
    }
}
