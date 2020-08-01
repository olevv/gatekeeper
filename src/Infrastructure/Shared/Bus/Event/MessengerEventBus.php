<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Event;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class MessengerEventBus implements EventBus
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function fire(EventInterface $event): void
    {
        try {
            $this->eventBus->dispatch($event);
        } catch (HandlerFailedException $e) {
            if ($e->getPrevious() instanceof Throwable) {
                throw $e->getPrevious();
            }

            throw $e;
        }
    }
}
