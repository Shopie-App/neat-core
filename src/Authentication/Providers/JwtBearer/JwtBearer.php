<?php

declare(strict_types=1);

namespace Neat\Authentication\Providers\JwtBearer;

use Neat\Authentication\Authentication;
use Neat\Authentication\AuthenticationResult;

final class JwtBearer extends Authentication
{
    public function __construct(private JwtBearerOptions $options)
    {
    }

    /**
     * TODO
     */
    public function authenticate(): AuthenticationResult
    {
        return new AuthenticationResult();
    }

    /**
     * TODO
     */
    public function signIn(): string|false
    {
        $token = (new JwtIssuer($this->options->key(), $this->options->issuer()))->issue(11, 300, $this->options->claims());
        return '';
    }
    
    /**
     * TODO
     */
    public function signOut(): void
    {
    }
}