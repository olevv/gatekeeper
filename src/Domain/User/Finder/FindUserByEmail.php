<?php

declare(strict_types=1);

namespace App\Domain\User\Finder;

use App\Domain\Shared\Exception\NotFoundException;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ViewModel\UserView;

interface FindUserByEmail
{
    /**
     * @throws NotFoundException
     */
    public function oneByEmail(Email $email): UserView;
}
