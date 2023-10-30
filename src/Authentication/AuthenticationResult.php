<?php

declare(strict_types=1);

namespace Neat\Authentication;

class AuthenticationResult
{
    private bool $succeeded = false;

    public function __construct()
    {
    }

    public function succeeded(): bool
    {
        return $this->succeeded;
    }

    public function success(): void
    {
        $this->succeeded = true;
    }
}