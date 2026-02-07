<?php

declare(strict_types=1);

namespace Neat\Middleware;

use Closure;
use Neat\Auth\JwtTokenParser;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Contracts\Security\ClaimsPrincipalInterface;

final class ClaimsTransformationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private JwtTokenParser $parser,
        private ClaimsPrincipalInterface $principal
    ) {}

    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        $header = $context->request()->header('Authorization') ?? '';

        $claims = str_starts_with($header, 'Bearer ') ? $this->parser->parse(substr($header, 7)) : [];

        $this->principal->setClaims($claims);

        // next middleware
        return $next($context);
    }
}