<?php

declare(strict_types=1);

namespace App\Domain\User\ViewModel;

/**
 * @psalm-suppress MissingConstructor
 */
final class UserView implements SerializableView
{
    public string $uuid;
    public string $email;
    public string $password_hash;
    public string $role;
    public string $status;
    public ?string $access_token;
    public ?string $access_token_expires;
    public string $created_at;
    public ?string $updated_at;

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'access_token' => $this->access_token,
            'access_token_expires' => $this->access_token_expires,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
