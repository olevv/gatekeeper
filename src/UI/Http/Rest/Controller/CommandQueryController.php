<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use App\Application\Command\Command;
use App\Application\Command\CommandBus;
use App\Application\Query\QueryBus;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommandQueryController extends QueryController
{
    private CommandBus $commandBus;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        UrlGeneratorInterface $router
    ) {
        parent::__construct($queryBus, $router);
        $this->commandBus = $commandBus;
    }

    protected function exec(Command $command): void
    {
        $this->commandBus->handle($command);
    }
}
