<?php

declare(strict_types=1);

namespace Neat\Authentication;

use Neat\Contracts\Authentication\AuthenticationFacadeInterface;
use Neat\Contracts\Authentication\AuthenticationInterface;

abstract class Authentication implements AuthenticationInterface, AuthenticationFacadeInterface
{
    abstract public function authenticate(string $challenge): AuthenticationResult;

    public function authenticateFromCookie(): AuthenticationResult
    {
        throw new \Exception('Method not implemented');
    }

    public function authenticateFromHeader(): AuthenticationResult
    {
        throw new \Exception('Method not implemented');
    }

    public function signIn(): mixed
    {
        throw new \Exception('Method not implemented');
    }
    
    public function signOut(): void
    {
        throw new \Exception('Method not implemented');
    }
}