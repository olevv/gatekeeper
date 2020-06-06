<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Domain\User\ValueObject\Email;
use App\Infrastructure\Shared\Bus\Command\Command;

final class SignInCommand implements Command
{
    /** @psalm-readonly */
    public Email $email;

    /** @psalm-readonly */
    public string $plainPassword;

    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }
}
