<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Event\Publisher;

use App\Domain\Shared\Event\DomainEvent;

interface EventPublisher
{
    public function handle(DomainEvent $domainEvent): void;

    public function publish(): void;
}
