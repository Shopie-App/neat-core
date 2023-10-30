<?php

declare(strict_types=1);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Neat\Authentication\Providers\JwtBearer\JwtIssuer;
use PHPUnit\Framework\TestCase;

final class JwtIssuerTest extends TestCase
{
    public function testJwtIssuer(): void
    {
        // vars
        $key = 'VFhnxPrMohnGE5z4fG9gmY8ZKZMGTTd4';
        $iss = 'test.domain.com';
        $sub = 1;
        $durationSecs = 300; // seconds token expires after

        // init
        $issuer = new JwtIssuer($key, $iss);

        // make token
        $token = $issuer->issue($sub, $durationSecs);

        // decode for assert
        $decoded = JWT::decode($token, new Key($key, 'HS256'));

        // assert
        $this->assertInstanceOf(JwtIssuer::class, $issuer);
        $this->assertEquals($iss, $decoded->iss);
        $this->assertEquals($iss, $decoded->iss);
        $this->assertEquals($sub, $decoded->sub);
    }
}