<?php

declare(strict_types=1);

namespace Neat\Middleware;

use Closure;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Contracts\Http\Routing\RoutingInterface;

use function Neat\Http\Status\NotFound;

final class Routing implements MiddlewareInterface
{
    public function __construct(private RoutingInterface $route) {}

    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        // match route to controller
        if (!$this->route->match($context->request()->uri(), $context->request()->method())) {

            NotFound(['error' => 'Invalid path requested']);

            return $context->response();
        }

        // run controller action
        $actionResult = $this->route->runAction();

        // set result to response
        $context->response()->setActionResult($actionResult);

        // next middleware
        return $next($context);
    }
}