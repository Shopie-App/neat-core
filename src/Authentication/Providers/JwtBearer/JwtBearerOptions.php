<?php

declare(strict_types=1);

namespace Neat\Authentication\Providers\JwtBearer;

use Neat\Contracts\Authentication\AuthenticationOptionsInterface;

class JwtBearerOptions
{
    public readonly string $key;

    public readonly string $audience;

    public readonly string $issuer;

    public readonly array $claims;

    public function __construct(AuthenticationOptionsInterface $options)
    {
        $this->key = $options->get('key');

        $this->audience = $options->get('audience');

        $this->issuer = $options->get('issuer');

        $this->claims = $options->get('claims');
    }
}