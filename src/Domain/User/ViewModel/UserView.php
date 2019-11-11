<?php

declare(strict_types=1);

namespace App\Domain\User\ViewModel;

final class UserView implements SerializableView
{
    /** @var string */
    public $uuid;
    /** @var string */
    public $email;
    /** @var string */
    public $password_hash;
    /** @var string */
    public $role;
    /** @var string */
    public $status;
    /** @var string|null */
    public $access_token;
    /** @var string|null */
    public $access_token_expires;
    /** @var string */
    public $created_at;
    /** @var string|null */
    public $updated_at;
    /** @var string|null */
    public $deleted_at;

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
        ];
    }
}
