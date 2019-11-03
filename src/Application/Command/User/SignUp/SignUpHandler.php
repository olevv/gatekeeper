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
    /**
     * @var UserRepository
     */
    private $userStore;
    /**
     * @var UniqueEmail
     */
    private $uniqueEmail;
    /**
     * @var Flusher
     */
    private $flusher;


    public function __construct(UserRepository $userStore, UniqueEmail $uniqueEmail, Flusher $flusher)
    {
        $this->userStore = $userStore;
        $this->uniqueEmail = $uniqueEmail;
        $this->flusher = $flusher;
    }

    public function __invoke(SignUpCommand $command)
    {
        $user = User::create($command->uuid, $command->credentials, $this->uniqueEmail);

        $this->userStore->store($user);

        $this->flusher->flush();
    }
}
