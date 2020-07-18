<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\ChangePassword;

use App\Application\Command\User\ChangePassword\ChangePasswordCommand;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Tests\Application\ApplicationTestCase;

final class ChangePasswordHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function change_password_given_command_should_changed_successfully(): void
    {
        $email = 'example123@test.ru';

        $uuid = $this->createUser($email, '123456789');

        $command = new ChangePasswordCommand($uuid->toString(), 'new_pass');

        $this->handle($command);

        $userView = $this->getUserViewByEmail($email);

        $hashedPassword = HashedPassword::fromHash($userView->password_hash);

        self::assertTrue($hashedPassword->match('new_pass'));
    }
}
