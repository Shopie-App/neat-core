<?php

declare(strict_types=1);

namespace Neat\Contracts\Authentication;

interface AuthenticationOptionsInterface
{
    public function get(string $key): mixed;
}