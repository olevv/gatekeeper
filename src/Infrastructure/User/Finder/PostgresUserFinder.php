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
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Email $email
     * @return UserView
     *
     * @throws NotFoundException
     */
    public function oneByEmail(Email $email): UserView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'uuid',
                'email',
                'password_hash',
                'role',
                'status',
                'access_token',
                'access_token_expires',
                'created_at',
                'updated_at',
                )
            ->from('users')
            ->where('email = :email')
            ->setParameter(':email', $email->toString())
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, UserView::class);

        $model = $stmt->fetch();

        if (!$model instanceof UserView) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param AccessToken $token
     * @return UserView
     *
     * @throws NotFoundException
     */
    public function oneByToken(AccessToken $token): UserView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'uuid',
                'email',
                'password_hash',
                'role',
                'status',
                'access_token',
                'access_token_expires',
                'created_at',
                'updated_at',
                )
            ->from('users')
            ->where('access_token = :token')
            ->setParameter(':token', $token->toString())
            ->execute();

        $stmt->setFetchMode(FetchMode::CUSTOM_OBJECT, UserView::class);

        $model = $stmt->fetch();

        if (!$model instanceof UserView) {
            throw new NotFoundException();
        }

        return $model;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return UserView[]
     */
    public function all(int $limit, int $offset): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'uuid',
                'email',
                'password_hash',
                'role',
                'status',
                'access_token',
                'access_token_expires',
                'created_at',
                'updated_at',
                'deleted_at',
                )
            ->from('users')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->execute();

        return $stmt->fetchAll(FetchMode::CUSTOM_OBJECT, UserView::class);
    }
}
