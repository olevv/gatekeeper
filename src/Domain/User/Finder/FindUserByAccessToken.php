<?php

declare(strict_types=1);

namespace App\Domain\User\Finder;

use App\Domain\Shared\Exception\NotFoundException;
use App\Domain\User\ValueObject\AccessToken;
use App\Domain\User\ViewModel\UserView;

interface FindUserByAccessToken
{
    /**
     * @throws NotFoundException
     */
    public function oneByToken(AccessToken $token): UserView;
}
