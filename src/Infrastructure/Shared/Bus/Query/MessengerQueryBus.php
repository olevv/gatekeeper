<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Query;

use App\Application\Query\Query;
use App\Application\Query\QueryBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

final class MessengerQueryBus implements QueryBus
{
    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function ask(Query $query)
    {
        try {
            $envelope = $this->queryBus->dispatch($query);

            /** @var HandledStamp $stamp */
            $stamp = $envelope->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (HandlerFailedException $e) {
            if ($e->getPrevious() instanceof Throwable) {
                throw $e->getPrevious();
            }

            throw $e;
        }
    }
}
