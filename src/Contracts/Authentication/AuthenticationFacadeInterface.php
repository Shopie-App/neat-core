<?php

declare(strict_types=1);

namespace Neat\Contracts\Authentication;

use Neat\Authentication\AuthenticationResult;

interface AuthenticationFacadeInterface
{
    public function authenticateFromCookie(): AuthenticationResult;

    public function authenticateFromHeader(): AuthenticationResult;
}