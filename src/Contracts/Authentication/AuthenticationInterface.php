<?php

declare(strict_types=1);

namespace Neat\Contracts\Authentication;

use Neat\Authentication\AuthenticationResult;

interface AuthenticationInterface
{
    public function authenticate(string $challenge): AuthenticationResult;

    public function signIn(): mixed;
    
    public function signOut(): void;
}