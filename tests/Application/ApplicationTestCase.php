<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Application\Command\User\SignUp\SignUpCommand;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ViewModel\UserView;
use App\Infrastructure\Shared\Bus\Command\Command;
use App\Infrastructure\Shared\Bus\Query\Query;
use App\Infrastructure\User\Finder\PostgresUserFinder;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class ApplicationTestCase extends KernelTestCase
{
    private ?CommandBus $commandBus = null;

    private ?CommandBus $queryBus = null;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->commandBus = self::$container->get('tactician.commandbus.command');

        $this->queryBus = self::$container->get('tactician.commandbus.query');
    }

    protected function tearDown(): void
    {
        $this->commandBus = null;
        $this->queryBus = null;
    }

    protected function ask(Query $query)
    {
        return $this->queryBus->handle($query);
    }

    protected function handle(Command $command): void
    {
        $this->commandBus->handle($command);
    }

    protected function createUser(string $email, string $password): UuidInterface
    {
        $uuid = Uuid::uuid4();

        $command = new SignUpCommand(
            $uuid->toString(),
            $email,
            $password
        );

        $this->handle($command);

        return $uuid;
    }

    protected function getUser(UuidInterface $uuid): User
    {
        $userStore = self::$container->get(UserRepository::class);

        return $userStore->get($uuid);
    }

    protected function getUserViewByEmail(string $email): UserView
    {
        $userFinder = self::$container->get(PostgresUserFinder::class);

        return $userFinder->oneByEmail(Email::fromString($email));
    }
}
