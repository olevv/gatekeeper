<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeEmail;

use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Specification\UniqueEmailSpecification;
use App\Infrastructure\Shared\Bus\Command\CommandHandler;
use App\Infrastructure\Shared\Flusher\Flusher;

final class ChangeEmailHandler implements CommandHandler
{
    private UserRepository $userStore;

    private Flusher $flusher;

    private UniqueEmailSpecification $uniqueEmail;

    public function __construct(UserRepository $userStore, Flusher $flusher, UniqueEmailSpecification $uniqueEmail)
    {
        $this->userStore = $userStore;
        $this->flusher = $flusher;
        $this->uniqueEmail = $uniqueEmail;
    }

    public function __invoke(ChangeEmailCommand $command): void
    {
        $user = $this->userStore->get($command->uuid);

        $user->changeEmail($command->email, $this->uniqueEmail);

        $this->flusher->flush();
    }
}
