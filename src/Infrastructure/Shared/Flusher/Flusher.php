<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Flusher;

use App\Domain\Shared\Aggregate\AggregateRoot;
use App\Infrastructure\Shared\Bus\Event\Event;
use App\Infrastructure\Shared\Bus\Event\EventBus;
use Doctrine\ORM\EntityManagerInterface;

final class Flusher
{
    private EntityManagerInterface $entityManager;

    private EventBus $eventBus;

    public function __construct(EntityManagerInterface $entityManager, EventBus $eventBus)
    {
        $this->entityManager = $entityManager;
        $this->eventBus = $eventBus;
    }

    public function flush(AggregateRoot $aggregate): void
    {
        $this->entityManager->flush();

        foreach ($aggregate->releaseEvents() as $domainEvent) {
            $this->eventBus->fire(new Event($domainEvent));
        }
    }
}
