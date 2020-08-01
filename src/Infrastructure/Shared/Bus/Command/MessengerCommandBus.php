<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Command;

use App\Application\Command\Command;
use App\Application\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class MessengerCommandBus implements CommandBus
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(Command $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            if ($e->getPrevious() instanceof Throwable) {
                throw $e->getPrevious();
            }

            throw $e;
        }
    }
}
