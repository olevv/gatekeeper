<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Application\Command\CommandHandler;
use App\Domain\Shared\Exception\NotFoundException;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Finder\FindUserByEmail;
use App\Domain\User\Repository\UserRepository;
use Ramsey\Uuid\Uuid;

final class SignInHandler implements CommandHandler
{
    private UserRepository $userRepository;

    private FindUserByEmail $findUserByEmail;

    public function __construct(UserRepository $userRepository, FindUserByEmail $findUserByEmail)
    {
        $this->userRepository = $userRepository;
        $this->findUserByEmail = $findUserByEmail;
    }

    public function __invoke(SignInCommand $command): void
    {
        try {
            $userView = $this->findUserByEmail->oneByEmail($command->email);
        } catch (NotFoundException $e) {
            throw new InvalidCredentialsException();
        }

        $user = $this->userRepository->get(Uuid::fromString($userView->uuid));

        $user->signIn($command->plainPassword);
    }
}
