<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Checker;

use App\Domain\User\Checker\CheckUserByEmail;
use App\Domain\User\User;
use App\Domain\User\ValueObject\Email;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class PostgresUserChecker implements CheckUserByEmail
{
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function existsEmail(Email $email): ?UuidInterface
    {
        $userId = $this->repository
            ->createQueryBuilder('user')
            ->select('user.uuid')
            ->where('user.email = :email')
            ->setParameter('email', $email->toString())
            ->getQuery()
            ->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
            ->getOneOrNullResult();

        return $userId['uuid'] ?? null;
    }
}
