<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignUp;

use App\Application\Command\CommandHandler;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Specification\UniqueEmailSpecification as UniqueEmail;
use App\Domain\User\User;
use App\Infrastructure\Shared\Flusher\Flusher;

final class SignUpHandler implements CommandHandler
{
    private UserRepository $userRepository;

    private UniqueEmail $uniqueEmail;

    private Flusher $flusher;

    public function __construct(UserRepository $userRepository, UniqueEmail $uniqueEmail, Flusher $flusher)
    {
        $this->userRepository = $userRepository;
        $this->uniqueEmail = $uniqueEmail;
        $this->flusher = $flusher;
    }

    public function __invoke(SignUpCommand $command): void
    {
        $user = User::create($command->uuid, $command->credentials, $this->uniqueEmail);

        $this->userRepository->store($user);

        $this->flusher->flush($user);
    }
}
