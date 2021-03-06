<?php

declare(strict_types=1);

namespace App\Domain\Shared\Specification;

abstract class Specification
{
    /**
     * @param mixed $value
     */
    abstract public function isSatisfiedBy($value): bool;
}
