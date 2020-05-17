<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Specification;

use App\Domain\Shared\Specification\Specification;
use App\Domain\User\Checker\CheckUserByEmail;
use App\Domain\User\Exception\EmailAlreadyExistException;
use App\Domain\User\Specification\UniqueEmailSpecification;
use App\Domain\User\ValueObject\Email;
use Doctrine\ORM\NonUniqueResultException;

final class PostgresUniqueEmailSpecification extends Specification implements UniqueEmailSpecification
{
    private CheckUserByEmail $checkUserByEmail;

    public function __construct(CheckUserByEmail $checkUserByEmail)
    {
        $this->checkUserByEmail = $checkUserByEmail;
    }

    /**
     * @param Email $email
     * @return bool
     *
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Email $email): bool
    {
        return $this->isSatisfiedBy($email);
    }

    public function isSatisfiedBy($value): bool
    {
        try {
            if ($this->checkUserByEmail->existsEmail($value)) {
                throw new EmailAlreadyExistException();
            }
        } catch (NonUniqueResultException $e) {
            throw new EmailAlreadyExistException();
        }

        return true;
    }
}
