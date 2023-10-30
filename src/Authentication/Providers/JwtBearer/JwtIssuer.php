<?php

declare(strict_types=1);

namespace Neat\Authentication\Providers\JwtBearer;

use Firebase\JWT\JWT;

class JwtIssuer
{
    public function __construct(private string $jwtKey, private string $issuer)
    {
    }

    public function issue(string|int $subject, int $durationSecs, array $privateClaims = [])
    {
        $time = time();

        $payload = [
            'iss' => $this->issuer,
            'sub' => $subject,
            /*'tid' => $track_id,*/
            'exp' => $time + $durationSecs,
            'nbf' => $time - 180,
            'iat' => $time,
            
        ];

        return JWT::encode($payload, $this->jwtKey, 'HS256');
    }
}