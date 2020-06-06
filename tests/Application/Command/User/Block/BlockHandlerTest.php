<?php

declare(strict_types=1);

namespace App\Tests\Application\Command\User\Block;

use App\Application\Command\User\Block\BlockCommand;
use App\Application\Command\User\SignUp\SignUpCommand;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\ValueObject\Email;
use App\Infrastructure\User\Finder\PostgresUserFinder;
use App\Tests\Application\ApplicationTestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class BlockHandlerTest extends ApplicationTestCase
{
    private UuidInterface $uuid;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uuid = Uuid::uuid4();

        $command = new SignUpCommand(
            $this->uuid->toString(),
            'test@test.com',
            'qwerty'
        );

        $this->exec($command);
    }

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
        $command = new BlockCommand($this->uuid->toString());

        $this->exec($command);

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
        $this->expectException(DomainException::class);

        $userStore = self::$container->get(UserRepository::class);

        $user = $userStore->get($this->uuid);

        $user->block();

        $command = new BlockCommand($this->uuid->toString());

        $this->exec($command);
    }
}
