<?php

declare(strict_types=1);

namespace App\Application\Command\User\Block;

use App\Application\Command\CommandHandler;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Shared\Flusher\Flusher;

final class BlockHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $userStore;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(UserRepository $userRepository, Flusher $flusher)
    {
        $this->userStore = $userRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param BlockCommand $command
     * @throws \App\Domain\Shared\Exception\DomainException
     */
    public function __invoke(BlockCommand $command)
    {
        $user = $this->userStore->get($command->uuid);

        $user->block();

        $this->flusher->flush();
    }
}
