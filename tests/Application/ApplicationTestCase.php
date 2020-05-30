<?php

declare(strict_types=1);

namespace App\Tests\Application;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class ApplicationTestCase extends KernelTestCase
{
    /** @var CommandBus|null */
    private $commandBus;

    /** @var CommandBus|null */
    private $queryBus;

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

    protected function ask($query)
    {
        return $this->queryBus->handle($query);
    }

    protected function exec($command): void
    {
        $this->commandBus->handle($command);
    }
}
