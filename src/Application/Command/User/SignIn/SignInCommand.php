<?php

declare(strict_types=1);

namespace App\Application\Command\User\SignIn;

use App\Domain\User\ValueObject\Email;

final class SignInCommand
{
    /**
     * @var Email
     */
    public $email;

    /**
     * @var string
     */
    public $plainPassword;

    /**
     * @param string $email
     * @param string $plainPassword
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }
}
