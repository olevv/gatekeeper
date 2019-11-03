<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repository;

use App\Domain\User\Exception\UserAlreadyExistsException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

final class UserStore implements UserRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function get(UuidInterface $uuid): User
    {
        $user = $this->repository->find($uuid->toString());

        if (null === $user) {
            throw new UserNotFoundException("User with id {$uuid->toString()} not found.");
        }

        return $user;
    }

    public function store(User $user): void
    {
        if ($this->entityManager->contains($user)) {
            throw new UserAlreadyExistsException("User with id {$user->uuid()} is already exists.");
        }

        $this->entityManager->persist($user);
    }
}
