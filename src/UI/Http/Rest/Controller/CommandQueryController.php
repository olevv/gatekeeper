<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Controller;

use League\Tactician\CommandBus;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommandQueryController extends QueryController
{
    private CommandBus $commandBus;

    public function __construct(
        CommandBus $commandBus,
        CommandBus $queryBus,
        UrlGeneratorInterface $router
    ) {
        parent::__construct($queryBus, $router);
        $this->commandBus = $commandBus;
    }

    protected function exec($command): void
    {
        $this->commandBus->handle($command);
    }
}
