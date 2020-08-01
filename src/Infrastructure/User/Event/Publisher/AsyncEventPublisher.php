<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Event\Publisher;

use App\Domain\Shared\Event\DomainEvent;
use App\Infrastructure\Shared\Bus\Event\Event;
use App\Infrastructure\Shared\Bus\Event\EventBus;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class AsyncEventPublisher implements EventPublisher, EventSubscriberInterface
{
    private array $events = [];

    private EventBus $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function handle(DomainEvent $domainEvent): void
    {
        $this->events[] = $domainEvent;
    }

    public function publish(): void
    {
        if (empty($this->events)) {
            return;
        }

        foreach ($this->events as $event) {
            $this->eventBus->fire(new Event($event));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => 'publish',
            ConsoleEvents::TERMINATE => 'publish',
        ];
    }
}
