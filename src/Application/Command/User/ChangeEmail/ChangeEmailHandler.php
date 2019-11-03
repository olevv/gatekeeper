<?php

declare(strict_types=1);

namespace App\Application\Command\User\ChangeEmail;

use App\Application\Command\CommandHandler;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Specification\UniqueEmailSpecification;
use App\Infrastructure\Shared\Flusher\Flusher;

final class ChangeEmailHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $userStore;
    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var UniqueEmailSpecification
     */
    private $uniqueEmail;

    public function __construct(UserRepository $userStore, Flusher $flusher, UniqueEmailSpecification $uniqueEmail)
    {
        $this->userStore = $userStore;
        $this->flusher = $flusher;
        $this->uniqueEmail = $uniqueEmail;
    }

    /**
     * @param ChangeEmailCommand $command
     * @throws \Exception
     */
    public function __invoke(ChangeEmailCommand $command)
    {
        $user = $this->userStore->get($command->uuid);

        $user->changeEmail($command->email, $this->uniqueEmail);

        $this->flusher->flush();
    }
}
