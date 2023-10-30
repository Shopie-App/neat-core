<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AuthenticationTest extends TestCase
{
    public function testAuthenticationSignIn(): void
    {
        /*
        // use jwt bearer
        $authentication = AuthenticationBuilder::create(JwtBearer::class, new AuthenticationOptions([
            'key' => 'VFhnxPrMohnGE5z4fG9gmY8ZKZMGTTd4',
            'audience' => 'My audience',
            'issuer' => 'test.domain.com',
            'claims' => []
        ]));


        // try sign in
        $authentication->signIn('steve.armen@gmail.com', '123123');
        */

        $this->assertTrue(true);
    }

    /*public function testAuthenticationSignOut(): void
    {
    }

    public function testAuthenticationAuthenticate(): void
    {
    }*/
}