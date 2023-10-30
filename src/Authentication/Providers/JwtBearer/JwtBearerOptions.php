<?php

declare(strict_types=1);

namespace Neat\Authentication\Providers\JwtBearer;

use Neat\Contracts\Authentication\AuthenticationOptionsInterface;

class JwtBearerOptions
{
    private readonly string $key;

    private readonly string $audience;

    private readonly string $issuer;

    private readonly array $claims;

    public function __construct(AuthenticationOptionsInterface $options)
    {
        $this->key = $options->get('key');

        $this->audience = $options->get('audience');

        $this->issuer = $options->get('issuer');

        $this->claims = $options->get('claims');
    }

    public function key(): string
    {
        return $this->key;
    }

    public function audience(): string
    {
        return $this->audience;
    }

    public function issuer(): string
    {
        return $this->issuer;
    }

    public function claims(): array
    {
        return $this->claims;
    }
}