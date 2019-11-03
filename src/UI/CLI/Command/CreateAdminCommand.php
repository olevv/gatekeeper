<?php

declare(strict_types=1);

namespace App\UI\CLI\Command;

use App\Application\Command\User\ChangeRole\ChangeRoleCommand;
use App\Application\Command\User\SignUp\SignUpCommand as CreateUser;
use App\Domain\User\ValueObject\Role;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateAdminCommand extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-admin')
            ->setDescription('Given email and password for generates a new admin.')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('uuid', InputArgument::OPTIONAL, 'User Uuid');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \Assert\AssertionFailedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var string $uuid */
        $uuid = $input->getArgument('uuid') ?: Uuid::uuid4()->toString();
        /** @var string $email */
        $email = $input->getArgument('email');
        /** @var string $password */
        $password = $input->getArgument('password');
        /** @var string $role */
        $role = Role::admin()->toString();

        $this->commandBus->handle(new CreateUser($uuid, $email, $password));

        $this->commandBus->handle(new ChangeRoleCommand($uuid, $role));

        $output->writeln('<info>Admin Created: </info>');
        $output->writeln('');
        $output->writeln("Email: $email");
    }
}
