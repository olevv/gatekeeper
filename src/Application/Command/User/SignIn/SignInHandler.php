<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Application\Command\CommandHandler;
use App\Domain\User\Checker\CheckUserByEmail;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Repository\UserRepository;

final class SignInHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $userStore;
    /**
     * @var CheckUserByEmail
     */
    private $userChecker;

    public function __construct(UserRepository $userStore, CheckUserByEmail $checker)
    {
        $this->userStore = $userStore;
        $this->userChecker = $checker;
    }

    public function __invoke(SignInCommand $command)
    {
        $uuid = $this->userChecker->existsEmail($command->email);

        if (null === $uuid) {
            throw new InvalidCredentialsException();
        }

        $user = $this->userStore->get($uuid);

        $user->signIn($command->plainPassword);
    }
}
