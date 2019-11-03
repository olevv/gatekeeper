<?php

declare(strict_types=1);

namespace App\Domain\User\Finder;

use App\Domain\Shared\Exception\NotFoundException;
use App\Domain\User\ViewModel\UserView;
use App\Domain\User\ValueObject\AccessToken;

interface FindUserByAccessToken
{
    /**
     * @param AccessToken $token
     * @return UserView
     *
     * @throws NotFoundException
     */
    public function oneByToken(AccessToken $token): UserView;
}
