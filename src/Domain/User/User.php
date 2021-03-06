<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\Aggregate\AggregateRoot;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Event\UserAccessTokenUpdated;
use App\Domain\User\Event\UserBlocked;
use App\Domain\User\Event\UserCreated;
use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\Event\UserPasswordChanged;
use App\Domain\User\Event\UserRoleChanged;
use App\Domain\User\Event\UserSignedIn;
use App\Domain\User\Event\UserUnblocked;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Specification\UniqueEmailSpecification;
use App\Domain\User\ValueObject\AccessToken;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\Role;
use App\Domain\User\ValueObject\Status;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"})
 * })
 */
final class User extends AggregateRoot
{
    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id
     */
    private UuidInterface $uuid;

    /** @ORM\Column(type="email", unique=true, length=60) */
    private Email $email;

    /** @ORM\Column(type="hashed_password", name="password_hash", length=255) */
    private HashedPassword $hashedPassword;

    /** @ORM\Column(type="role", length=30) */
    private Role $role;

    /** @ORM\Column(type="status", length=16) */
    private Status $status;

    /** @ORM\Column(type="string", nullable=true, length=255) */
    private ?string $accessToken;

    /** @ORM\Column(type="datetime_immutable", nullable=true) */
    private ?\DateTimeImmutable $accessTokenExpires;

    /** @ORM\Column(type="datetime_immutable") */
    private \DateTimeImmutable $createdAt;

    /** @ORM\Column(type="datetime_immutable", nullable=true) */
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

        $this->recordEvent(
            new UserCreated($credentials, $this->role, $this->status, $uuid, Uuid::uuid4(), $this->createdAt)
        );
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function signIn(string $plainPassword): void
    {
        if (!$this->hashedPassword->match($plainPassword)) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        $this->recordEvent(new UserSignedIn($this->email, $this->uuid, Uuid::uuid4(), new \DateTimeImmutable('now')));
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

        $this->recordEvent(new UserBlocked($blocked, $this->uuid, Uuid::uuid4(), new \DateTimeImmutable('now')));
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

        $this->recordEvent(new UserUnblocked($active, $this->uuid, Uuid::uuid4(), new \DateTimeImmutable('now')));
    }

    /**
     * @throws \Exception
     */
    public function changePassword(HashedPassword $password): void
    {
        $this->hashedPassword = $password;
        $this->updatedAt = new \DateTimeImmutable('now');

        $this->recordEvent(new UserPasswordChanged($password, $this->uuid, Uuid::uuid4(), new \DateTimeImmutable('now')));
    }

    /**
     * @throws \Exception
     */
    public function changeEmail(Email $email, UniqueEmailSpecification $uniqueEmail): void
    {
        $uniqueEmail->isUnique($email);

        $this->email = $email;
        $this->updatedAt = new \DateTimeImmutable('now');

        $this->recordEvent(new UserEmailChanged($email, $this->uuid, Uuid::uuid4(), new \DateTimeImmutable('now')));
    }

    /**
     * @throws \Exception
     */
    public function updateAccessToken(AccessToken $token): void
    {
        $this->accessToken = $token->toString();
        $this->accessTokenExpires = $token->expiresAt();
        $this->updatedAt = new \DateTimeImmutable('now');

        $this->recordEvent(new UserAccessTokenUpdated($token, $this->uuid, Uuid::uuid4(), new \DateTimeImmutable('now')));
    }

    /**
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

        $this->recordEvent(new UserRoleChanged($role, $this->uuid, Uuid::uuid4(), new \DateTimeImmutable('now')));
    }

    public function uuid(): string
    {
        return $this->uuid->toString();
    }
}
