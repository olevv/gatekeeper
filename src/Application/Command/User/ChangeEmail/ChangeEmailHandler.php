<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeEmail;

use App\Application\Command\CommandHandler;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Specification\UniqueEmailSpecification;
use App\Infrastructure\Shared\Flusher\Flusher;

final class ChangeEmailHandler implements CommandHandler
{
    private UserRepository $userRepository;

    private Flusher $flusher;

    private UniqueEmailSpecification $uniqueEmail;

    public function __construct(UserRepository $userRepository, Flusher $flusher, UniqueEmailSpecification $uniqueEmail)
    {
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
        $this->uniqueEmail = $uniqueEmail;
    }

    public function __invoke(ChangeEmailCommand $command): void
    {
        $user = $this->userRepository->get($command->uuid);

        $user->changeEmail($command->email, $this->uniqueEmail);

        $this->flusher->flush($user);
    }
}
