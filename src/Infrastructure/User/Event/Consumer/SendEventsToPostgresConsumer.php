<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Event\Consumer;

use App\Infrastructure\Shared\Bus\Event\Event;
use App\Infrastructure\Shared\Bus\Event\EventHandler;
use App\Infrastructure\User\Exception\DatabaseException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

final class SendEventsToPostgresConsumer implements EventHandler
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(Event $event): void
    {
        $domainEvent = $event->getDomainEvent();

        $id = $this->connection->quote($domainEvent->eventId());
        $aggregateId = $this->connection->quote($domainEvent->aggregateId());
        $name = $this->connection->quote($domainEvent->name());
        $payload = $this->connection->quote(json_encode($domainEvent->payload()));
        $occurredOn = $this->connection->quote($domainEvent->occurredOn());

        try {
            $this->connection->executeUpdate(
                <<<SQL
                    INSERT INTO domain_events (id,  aggregate_id, name,  payload,  occurred_on) 
                                VALUES ($id, $aggregateId, $name, $payload, $occurredOn);
SQL
            );
        } catch (DBALException $e) {
            throw new DatabaseException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}
