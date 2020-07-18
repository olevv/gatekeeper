<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\Block;

use App\Application\Command\User\Block\BlockCommand;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\User\Finder\PostgresUserFinder;
use App\Tests\Application\ApplicationTestCase;

final class BlockHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function block_user_given_user_exist_blocked_successfully(): void
    {
        $uuid = $this->createUser('test@test.com', 'qwerty');

        $command = new BlockCommand($uuid->toString());

        $this->handle($command);

        $userFinder = self::$container->get(PostgresUserFinder::class);

        $userView = $userFinder->oneByEmail(Email::fromString('test@test.com'));

        self::assertSame('BLOCKED', $userView->status);
    }

    /**
     * @test
     *
     * @group integration
     *
     * @throws \Exception
     * @throws \Assert\AssertionFailedException
     */
    public function block_user_given_user_is_already_blocked_throw_domain_exception(): void
    {
        $uuid = $this->createUser('test123@test.com', 'qwerty123');

        $this->expectException(DomainException::class);

        $user = $this->getUser($uuid);

        $user->block();

        $command = new BlockCommand($uuid->toString());

        $this->handle($command);
    }
}
