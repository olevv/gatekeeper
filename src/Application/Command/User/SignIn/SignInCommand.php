<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Domain\User\ValueObject\Email;

final class SignInCommand
{
    public Email $email;
    public string $plainPassword;

    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }
}
