<?php

declare(strict_types=1);

namespace App\Application\Command\User\Unblock;

use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Shared\Bus\Command\CommandHandler;
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

    public function __invoke(UnblockCommand $command): void
    {
        $user = $this->userStore->get($command->uuid);

        $user->unblock();

        $this->flusher->flush();
    }
}
