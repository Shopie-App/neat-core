<?php

declare(strict_types=1);

namespace Neat\Authentication;

class AuthenticationResult
{
    public function __construct(private readonly bool $succeeded)
    {
    }

    public function succeeded(): bool
    {
        return $this->succeeded;
    }
}