<?php

declare(strict_types=1);

namespace Neat\Authentication;

use Neat\Contracts\Authentication\AuthenticationInterface;

abstract class Authentication implements AuthenticationInterface
{
    abstract public function authenticate(): AuthenticationResult;

    public function signIn(): mixed
    {
        throw new \Exception('Method not implemented');
    }
    
    public function signOut(): void
    {
        throw new \Exception('Method not implemented');
    }
}