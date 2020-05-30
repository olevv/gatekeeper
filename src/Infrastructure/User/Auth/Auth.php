<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Auth;

use App\Domain\User\ValueObject\AccessToken;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Role;
use App\Domain\User\ValueObject\Status;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class Auth implements UserInterface
{
    private UuidInterface $userId;

    private Email $email;

    private HashedPassword $password;

    private Role $role;

    private Status $status;

    private string $accessToken;

    private ?\DateTimeImmutable $accessTokenExpires;

    public function __construct(
        UuidInterface $userId,
        Credentials $credentials,
        Role $role,
        Status $status,
        AccessToken $accessToken
    ) {
        $this->userId = $userId;
        $this->email = $credentials->email;
        $this->password = $credentials->password;
        $this->role = $role;
        $this->status = $status;
        $this->accessToken = $accessToken->toString();
        $this->accessTokenExpires = $accessToken->expiresAt();
    }

    public function getUsername(): string
    {
        return $this->email->toString();
    }

    public function getPassword(): string
    {
        return $this->password->toString();
    }

    public function getRoles(): array
    {
        return [$this->role->toString()];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function canSeeUserInfo(Email $email): bool
    {
        return $this->isAdminActive() || $email->equals($this->email);
    }

    public function canBlockUser(): bool
    {
        return $this->isAdminActive();
    }

    public function canUnblockUser(): bool
    {
        return $this->isAdminActive();
    }

    public function canChangePassword(UuidInterface $userId): bool
    {
        return $this->isAdminActive() || $userId->equals($this->userId);
    }

    public function canChangeEmail(UuidInterface $userId): bool
    {
        return $this->isAdminActive() || $userId->equals($this->userId);
    }

    public function canSeeUsers(): bool
    {
        return $this->isAdminActive();
    }

    private function isAdminActive(): bool
    {
        return $this->role->equals(Role::admin()) && $this->status->equals(Status::active());
    }
}
