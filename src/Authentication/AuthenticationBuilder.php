<?php

declare(strict_types=1);

namespace Neat\Authentication;

use Neat\Contracts\Authentication\AuthenticationInterface;
use Neat\Contracts\Authentication\AuthenticationOptionsInterface;

class AuthenticationBuilder
{
    public function __construct(
        private string $authProvider,
        private AuthenticationOptionsInterface $options
    ) {
    }

    public function addCookie(string $name): AuthenticationBuilder
    {
        return $this;
    }

    public function addHeader(string $name): AuthenticationBuilder
    {
        return $this;
    }

    public function addChallenge(string $name): AuthenticationBuilder
    {
        return $this;
    }

    /**
     * getResult()
     */
    public function build(): AuthenticationInterface|null
    {
        return AuthenticationFactory::create($this->authProvider, $this->options);
    }
}