<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Finder;

use App\Domain\Shared\Exception\NotFoundException;
use App\Domain\User\Finder\FindUserByAccessToken;
use App\Domain\User\Finder\FindUserByEmail;
use App\Domain\User\Finder\FindUsers;
use App\Domain\User\ValueObject\AccessToken;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ViewModel\UserView;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

final class PostgresUserFinder implements FindUserByEmail, FindUserByAccessToken, FindUsers
{
    private const FIELDS = [
        'email',
        'password_hash',
        'role',
        'status',
        'access_token',
        'access_token_expires',
        'created_at',
        'updated_at',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws NotFoundException
     */
    public function oneByEmail(Email $email): UserView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from('users')
            ->where('email = :email')
            ->setParameter(':email', $email->toString())
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, UserView::class);

        $model = $stmt->fetch();

        if (!$model instanceof UserView) {
            throw new NotFoundException('Not found user for email: ' . $email->toString());
        }

        return $model;
    }

    /**
     * @throws NotFoundException
     */
    public function oneByToken(AccessToken $token): UserView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from('users')
            ->where('access_token = :token')
            ->setParameter(':token', $token->toString())
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, UserView::class);

        $model = $stmt->fetch();

        if (!$model instanceof UserView) {
            throw new NotFoundException('Not found user for token: ' . $token->toString());
        }

        return $model;
    }

    /**
     * @return UserView[]
     */
    public function all(int $limit, int $offset): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from('users')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, UserView::class);

        return $stmt->fetchAll();
    }
}
