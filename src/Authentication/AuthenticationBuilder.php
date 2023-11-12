<?php

declare(strict_types=1);

namespace Neat\Authentication;

use Neat\Contexts\HttpContext;
use Neat\Contracts\Authentication\AuthenticationOptionsInterface;

class AuthenticationBuilder
{
    private string $cookieName;

    private string $headerName;

    public function __construct(
        private HttpContext $httpContext,
        private string $authProvider,
        private AuthenticationOptionsInterface $options
    ) {
    }

    public function addCookie(string $name): AuthenticationBuilder
    {
        $this->cookieName = $name;

        return $this;
    }

    public function addHeader(string $name): AuthenticationBuilder
    {
        $this->headerName = $name;

        return $this;
    }

    /**
     * getResult()
     */
    public function build(): Authentication|null
    {
        if (($authenticator = AuthenticationFactory::create($this->authProvider, $this->options)) === null) {

            return null;
        }

        $authProxy = new AuthenticationFacade($authenticator);

        $authenticator = null;

        if ((new \ReflectionProperty($this, 'cookieName'))->isInitialized($this)) {
            
            $authProxy->setCookieChallenge($this->httpContext->request()->cookie($this->cookieName));
        }

        if ((new \ReflectionProperty($this, 'headerName'))->isInitialized($this)) {
            
            $authProxy->setHeaderChallenge($this->httpContext->request()->header($this->headerName));
        }

        return $authProxy;
    }
}