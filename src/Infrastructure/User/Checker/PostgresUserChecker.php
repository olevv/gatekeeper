<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Checker;

use App\Domain\User\Checker\CheckUserByEmail;
use App\Domain\User\ValueObject\Email;
use Doctrine\DBAL\Connection;

final class PostgresUserChecker implements CheckUserByEmail
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function existsEmail(Email $email): bool
    {
        $uuid = $this->connection->createQueryBuilder()
            ->select('uuid')
            ->from('users')
            ->where('email = :email')
            ->setParameter(':email', $email->toString())
            ->execute()
            ->fetchColumn();

        return $uuid !== false;
    }
}
