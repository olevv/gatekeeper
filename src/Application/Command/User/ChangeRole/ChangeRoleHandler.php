<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeRole;

use App\Application\Command\CommandHandler;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Shared\Flusher\Flusher;

final class ChangeRoleHandler implements CommandHandler
{
    private UserRepository $userStore;

    private Flusher $flusher;

    public function __construct(UserRepository $userStore, Flusher $flusher)
    {
        $this->userStore = $userStore;
        $this->flusher = $flusher;
    }

    public function __invoke(ChangeRoleCommand $command)
    {
        $user = $this->userStore->get($command->uuid);

        $user->changeRole($command->role);

        $this->flusher->flush();
    }
}
