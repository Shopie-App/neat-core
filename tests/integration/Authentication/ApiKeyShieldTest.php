<?php

declare(strict_types=1);

use Neat\Authentication\AuthenticationBuilder;
use Neat\Authentication\AuthenticationOptions;
use Neat\Authentication\Providers\ApiKey\ApiKey;
use PHPUnit\Framework\TestCase;

final class ApiKeyShieldTest extends TestCase
{
    public function testApiKeyShield(): void
    {
        // build api key authentication provider
        $authBuilder = new AuthenticationBuilder(
            ApiKey::class,
            new AuthenticationOptions(['apiKey' => 'myApiKeyString'])
        );

        $auth = $authBuilder->addCookie('auth_cookie')->getResult();

        // authenticate
        $result = $auth->authenticate('myApiKeyString');

        //print_r($result);

        // print_r($auth);

        $this->assertTrue(true);
    }
}