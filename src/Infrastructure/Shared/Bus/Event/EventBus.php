<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Event;

interface EventBus
{
    public function fire(EventInterface $event): void;
}
