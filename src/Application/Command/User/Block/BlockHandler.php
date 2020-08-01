<?php

declare(strict_types=1);

namespace App\Application\Command\User\Block;

use App\Application\Command\CommandHandler;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Shared\Flusher\Flusher;

final class BlockHandler implements CommandHandler
{
    private UserRepository $userRepository;

    private Flusher $flusher;

    public function __construct(UserRepository $userRepository, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    public function __invoke(BlockCommand $command): void
    {
        $user = $this->userRepository->get($command->uuid);

        $user->block();

        $this->flusher->flush($user);
    }
}
