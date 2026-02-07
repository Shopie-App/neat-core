<?php

declare(strict_types=1);

namespace Neat\Http;

use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareChainInterface;
use Neat\Contracts\Http\ResponseInterface;

class MiddlewareChain implements MiddlewareChainInterface
{
    private array $chain = [];

    public function add(string $middleware, ...$params): void
    {
        $this->chain[] = [$middleware, $params];
    }

    public function process(HttpContext $context): ResponseInterface
    {
        return $this->next($context, 0);
    }

    private function next(HttpContext $context, int $index): ResponseInterface
    {
        if (!isset($this->chain[$index])) {
            return $context->response();
        }

        [$middleware, $params] = $this->chain[$index];

        $next = fn (HttpContext $ctx) => $this->next($ctx, $index + 1);

        return (new $middleware(...$params))->handle($context, $next);
    }
}