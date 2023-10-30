<?php

declare(strict_types=1);

namespace Neat\Contracts\Http;

use Closure;
use Neat\Contexts\HttpContext;

interface MiddlewareInterface
{
    public function handle(HttpContext $context, Closure $next): ResponseInterface;
}