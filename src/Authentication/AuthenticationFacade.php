<?php

declare(strict_types=1);

namespace Neat\Authentication;

use Neat\Contracts\Authentication\AuthenticationInterface;

class AuthenticationFacade extends Authentication
{
    /**
     * The value of the cookie that is used for authentication.
     * @var string
     */
    private ?string $authCookieValue = null;

    /**
     * The value of the header that is used for authentication.
     * @var string
     */
    private ?string $authHeaderValue = null;

    public function __construct(private AuthenticationInterface $authProvider)
    {
        
    }

    public function setCookieChallenge(string $value): void
    {
        $this->authCookieValue = $value;
    }

    public function setHeaderChallenge(string $value): void
    {
        $this->authHeaderValue = $value;
    }

    public function authenticateFromCookie(): AuthenticationResult
    {
        return $this->authProvider->authenticate($this->authCookieValue);
    }

    public function authenticateFromHeader(): AuthenticationResult
    {
        return $this->authProvider->authenticate($this->authHeaderValue);
    }

    public function authenticate(string $challenge): AuthenticationResult
    {
        return $this->authProvider->authenticate($challenge);
    }
}