<?php

declare(strict_types=1);

namespace App\Application\Command\User\Unblock;

use App\Application\Command\CommandHandler;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Shared\Flusher\Flusher;

final class UnblockHandler implements CommandHandler
{
    private UserRepository $userStore;
    private Flusher $flusher;

    public function __construct(UserRepository $userStore, Flusher $flusher)
    {
        $this->userStore = $userStore;
        $this->flusher = $flusher;
    }

    /**
     * @param UnblockCommand $command
     * @throws \App\Domain\Shared\Exception\DomainException
     */
    public function __invoke(UnblockCommand $command)
    {
        $user = $this->userStore->get($command->uuid);

        $user->unblock();

        $this->flusher->flush();
    }
}
