<?php

declare(strict_types=1);

use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Http\MiddlewareChain;
use PHPUnit\Framework\TestCase;

final class MiddlewareChainTest extends TestCase
{
    public function testProcessCanBeCalledMultipleTimesWithoutEmptyingChain(): void
    {
        $context = $this->createStub(HttpContext::class);
        $response = $this->createStub(ResponseInterface::class);
        
        // Ensure the context returns a response when the chain finishes
        $context->method('response')->willReturn($response);

        $chain = new MiddlewareChain();
        $chain->add(SpyMiddleware::class);

        // Reset spy counter
        SpyMiddleware::$runCount = 0;

        // Run 1
        $result1 = $chain->process($context);
        $this->assertSame($response, $result1);
        $this->assertSame(1, SpyMiddleware::$runCount, 'Middleware should run once on first call');

        // Run 2 (This verifies the chain was cloned and not consumed)
        $result2 = $chain->process($context);
        $this->assertSame($response, $result2);
        $this->assertSame(2, SpyMiddleware::$runCount, 'Middleware should run again on second call');
    }
}

/**
 * Helper class to track execution
 */
class SpyMiddleware implements MiddlewareInterface
{
    public static int $runCount = 0;

    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        self::$runCount++;
        return $next($context);
    }
}