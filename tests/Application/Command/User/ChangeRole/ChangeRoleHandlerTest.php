<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\ChangeRole;

use App\Application\Command\User\ChangeRole\ChangeRoleCommand;
use App\Tests\Application\ApplicationTestCase;

final class ChangeRoleHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function change_user_role_given_command_should_changed_successfully(): void
    {
        $uuid = $this->createUser('example@tests.ru', '1234567');

        $command = new ChangeRoleCommand($uuid->toString(), 'ROLE_ADMIN');

        $this->handle($command);

        $userView = $this->getUserViewByEmail('example@tests.ru');

        self::assertEquals('ROLE_ADMIN', $userView->role);
    }
}
