<?php

declare(strict_types=1);

namespace Neat\Auth;

final class JwtTokenParser
{
    public function parse(string $token): array
    {
        // split the JWT (Header.Payload.Signature)
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return [];
        }

        // decode the payload (the middle part)
        $payload = $parts[1];

        $remainder = strlen($payload) % 4;
        
        if ($remainder) {
            $payload .= str_repeat('=', 4 - $remainder);
        }

        $json = base64_decode(strtr($payload, '-_', '+/'), true);
        
        if (!$json) {
            return [];
        }

        return json_decode($json, true) ?? [];
    }
}