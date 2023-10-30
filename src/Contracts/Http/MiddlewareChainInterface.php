<?php

declare(strict_types=1);

namespace Neat\Contracts\Http;

use Neat\Contexts\HttpContext;

interface MiddlewareChainInterface
{
    public function add(string $middleware, ...$params): void;

    public function process(HttpContext $context): ResponseInterface;
}