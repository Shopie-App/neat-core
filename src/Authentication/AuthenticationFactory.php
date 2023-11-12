<?php

declare(strict_types=1);

namespace Neat\Authentication;

use Neat\Authentication\Providers\ApiKey\ApiKey;
use Neat\Authentication\Providers\ApiKey\ApiKeyOptions;
use Neat\Authentication\Providers\JwtBearer\JwtBearer;
use Neat\Authentication\Providers\JwtBearer\JwtBearerOptions;
use Neat\Contracts\Authentication\AuthenticationInterface;
use Neat\Contracts\Authentication\AuthenticationOptionsInterface;

/**
 * A factory that builds authentication providers
 */
class AuthenticationFactory
{
    public static function create(string $provider, AuthenticationOptionsInterface $options): AuthenticationInterface|null
    {
        $authProvider = null;

        switch ($provider) {
            case 'ApiKey':
                $authProvider = new ApiKey(new ApiKeyOptions($options));
                break;
            case 'JwtBearer':
                $authProvider = new JwtBearer(new JwtBearerOptions($options));
                break;
        }
        
        return $authProvider;
    }
}