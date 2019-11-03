<?php

declare(strict_types=1);

namespace App\Domain\User\ViewModel;

interface SerializableView
{
    public function toArray(): array;
}
