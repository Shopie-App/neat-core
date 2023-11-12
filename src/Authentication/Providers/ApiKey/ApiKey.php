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

    public function authenticate(string $challenge): AuthenticationResult
    {
        $result = strcmp($challenge, $this->options->apiKey);

        if ($result != 0) {

            return new AuthenticationResult(false);
        }

        return new AuthenticationResult(true);
    }
}