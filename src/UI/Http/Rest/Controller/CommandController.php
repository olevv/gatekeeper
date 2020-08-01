<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Application\Command\Command;
use App\Application\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class CommandController extends AbstractController
{
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    protected function exec(Command $command): void
    {
        $this->commandBus->handle($command);
    }
}
