<?php

declare(strict_types=1);

namespace App\Application\Command\User\Block;

use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Shared\Bus\Command\CommandHandler;
use App\Infrastructure\Shared\Flusher\Flusher;

final class BlockHandler implements CommandHandler
{
    private UserRepository $userStore;

    private Flusher $flusher;

    public function __construct(UserRepository $userRepository, Flusher $flusher)
    {
        $this->userStore = $userRepository;
        $this->flusher = $flusher;
    }

    public function __invoke(BlockCommand $command): void
    {
        $user = $this->userStore->get($command->uuid);

        $user->block();

        $this->flusher->flush();
    }
}
