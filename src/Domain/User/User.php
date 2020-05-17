<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Specification\UniqueEmailSpecification;
use App\Domain\User\ValueObject\AccessToken;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Role;
use App\Domain\User\ValueObject\Status;
use Ramsey\Uuid\UuidInterface;

final class User
{
    private UuidInterface $uuid;
    private Email $email;
    private HashedPassword $hashedPassword;
    private Role $role;
    private Status $status;
    private ?string $accessToken;
    private ?\DateTimeImmutable $accessTokenExpires;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public static function create(
        UuidInterface $uuid,
        Credentials $credentials,
        UniqueEmailSpecification $uniqueEmail
    ): self {
        return new self($uuid, $credentials, $uniqueEmail);
    }

    private function __construct(UuidInterface $uuid, Credentials $credentials, UniqueEmailSpecification $uniqueEmail)
    {
        $uniqueEmail->isUnique($credentials->email);

        $this->uuid = $uuid;
        $this->email = $credentials->email;
        $this->hashedPassword = $credentials->password;
        $this->role = Role::user();
        $this->status = Status::active();
        $this->createdAt = new \DateTimeImmutable('now');
    }

    /**
     * @param string $plainPassword
     *
     * @throws InvalidCredentialsException
     */
    public function signIn(string $plainPassword): void
    {
        if (!$this->hashedPassword->match($plainPassword)) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }
    }

    /**
     * @throws DomainException
     */
    public function block(): void
    {
        $blocked = Status::blocked();

        if ($this->status->equals($blocked)) {
            throw new DomainException('User is already blocked.');
        }

        $this->status = $blocked;
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    /**
     * @throws DomainException
     */
    public function unblock(): void
    {
        $active = Status::active();

        if ($this->status->equals($active)) {
            throw new DomainException('User is already active.');
        }

        $this->status = $active;
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    /**
     * @param HashedPassword $password
     *
     * @throws \Exception
     */
    public function changePassword(HashedPassword $password): void
    {
        $this->hashedPassword = $password;
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    /**
     * @param Email $email
     * @param UniqueEmailSpecification $uniqueEmail
     *
     * @throws \Exception
     */
    public function changeEmail(Email $email, UniqueEmailSpecification $uniqueEmail): void
    {
        $uniqueEmail->isUnique($email);

        $this->email = $email;
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    /**
     * @param AccessToken $token
     *
     * @throws \Exception
     */
    public function updateAccessToken(AccessToken $token): void
    {
        $this->accessToken = $token->toString();
        $this->accessTokenExpires = $token->expiresAt();
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    /**
     * @param Role $role
     * @throws \Exception
     * @throws \DomainException
     */
    public function changeRole(Role $role): void
    {
        if ($this->role->equals($role)) {
            throw new DomainException('Role is already same.');
        }

        $this->role = $role;
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    public function uuid(): string
    {
        return $this->uuid->toString();
    }
}
