<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Infrastructure\Shared\Bus\Command\Command;
use App\Infrastructure\Shared\Bus\Query\Query;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class ApplicationTestCase extends KernelTestCase
{
    private ?CommandBus $commandBus = null;

    private ?CommandBus $queryBus = null;

    protected function setUp(): void
    {
        static::bootKernel();

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

    protected function exec(Command $command): void
    {
        $this->commandBus->handle($command);
    }
}
