<?php

declare(strict_types=1);

namespace App\Application\Query\Auth\GetToken;

use App\Domain\User\ValueObject\Email;

final class GetTokenQuery
{
    /**
     * @var Email
     */
    public $email;

    /**
     * @param string $email
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}
