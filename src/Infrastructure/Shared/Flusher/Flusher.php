<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Flusher;

use Doctrine\ORM\EntityManagerInterface;

final class Flusher
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
