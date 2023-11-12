<?php

declare(strict_types=1);

use Neat\Authentication\AuthenticationBuilder;
use Neat\Authentication\AuthenticationOptions;
use Neat\Contexts\HttpContext;
use Neat\Http\Request;
use PHPUnit\Framework\TestCase;

final class ApiKeyShieldTest extends TestCase
{
    public function testApiKeyShield(): void
    {
        // our test key
        $apiKey = 'K1BqLujcc1OjlDgGayYUr9n8PQ7tDgmf';

        // mocked http request
        $req = $this->createStub(Request::class);
        $req->expects($this->any())->method('cookie')->willReturn($apiKey);
        $req->expects($this->any())->method('header')->willReturn($apiKey);

        // mocked http context
        $ctx = $this->createStub(HttpContext::class);
        $ctx->expects($this->any())->method('request')->willReturn($req);

        // build api key authentication provider
        $authBuilder = new AuthenticationBuilder(
            $ctx,
            'ApiKey',
            new AuthenticationOptions(['apiKey' => $apiKey])
        );

        $auth = $authBuilder
        ->addCookie('auth_cookie')
        ->addHeader('X-Api-Key')
        ->build();

        // authenticate and assert
        $result = $auth->authenticateFromCookie();
        $this->assertTrue($result->succeeded());

        // authenticate and assert
        $result = $auth->authenticateFromHeader();
        $this->assertTrue($result->succeeded());

        // test failure
        // build api key authentication provider
        $authBuilder = new AuthenticationBuilder(
            $ctx,
            'ApiKey',
            new AuthenticationOptions(['apiKey' => 'my_wrong_api_key_value'])
        );

        $auth = $authBuilder
        ->addCookie('auth_cookie')
        ->addHeader('X-Api-Key')
        ->build();

        // authenticate and assert
        $result = $auth->authenticateFromCookie();
        $this->assertFalse($result->succeeded());

        // authenticate and assert
        $result = $auth->authenticateFromHeader();
        $this->assertFalse($result->succeeded());
    }
}