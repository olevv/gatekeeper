<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\Unblock;

use App\Application\Command\User\Unblock\UnblockCommand;
use App\Tests\Application\ApplicationTestCase;

final class UnblockHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function unblock_user_given_command_should_unblocked_successfully(): void
    {
        $email = 'example123@test.ru';

        $uuid = $this->createUser($email, '123456789');

        $user = $this->getUser($uuid);

        $user->block();

        $command = new UnblockCommand($uuid->toString());

        $this->handle($command);

        $userView = $this->getUserViewByEmail($email);

        self::assertEquals('ACTIVE', $userView->status);
    }
}
