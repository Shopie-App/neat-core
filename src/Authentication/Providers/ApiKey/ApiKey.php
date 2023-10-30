<?php

declare(strict_types=1);

namespace Neat\Authentication\Providers\ApiKey;

use Neat\Authentication\Authentication;
use Neat\Authentication\AuthenticationResult;

final class ApiKey extends Authentication
{
    public function __construct(private ApiKeyOptions $options)
    {
    }

    /**
     * TODO
     */
    public function authenticate(): AuthenticationResult
    {
        return new AuthenticationResult();
    }
}