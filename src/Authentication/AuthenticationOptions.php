<?php

declare(strict_types=1);

namespace Neat\Authentication;

use Neat\Contracts\Authentication\AuthenticationOptionsInterface;

class AuthenticationOptions implements AuthenticationOptionsInterface
{
    public readonly array $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function get(string $key): mixed
    {
        return $this->options[$key] ?? throw new \Exception('Option null exception');
    }
}