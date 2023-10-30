<?php

declare(strict_types=1);

namespace Neat\Authentication\Providers\ApiKey;

use Neat\Contracts\Authentication\AuthenticationOptionsInterface;

readonly class ApiKeyOptions
{
    public string $apiKey;

    public function __construct(AuthenticationOptionsInterface $options)
    {
        $this->apiKey = $options->get('apiKey');
    }
}