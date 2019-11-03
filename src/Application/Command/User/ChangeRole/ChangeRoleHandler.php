<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeRole;

use App\Application\Command\CommandHandler;
use App\Domain\User\Repository\UserRepository;
use App\Infrastructure\Shared\Flusher\Flusher;

final class ChangeRoleHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $userStore;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(UserRepository $userStore, Flusher $flusher)
    {
        $this->userStore = $userStore;
        $this->flusher = $flusher;
    }

    /**
     * @param ChangeRoleCommand $command
     * @throws \Exception
     */
    public function __invoke(ChangeRoleCommand $command)
    {
        $user = $this->userStore->get($command->uuid);

        $user->changeRole($command->role);

        $this->flusher->flush();
    }
}
