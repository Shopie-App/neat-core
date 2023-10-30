<?php

declare(strict_types=1);

namespace Neat\Http;

use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareChainInterface;

class MiddlewareChain implements MiddlewareChainInterface
{
    public function __construct(private \SplQueue $chain = new \SplQueue())
    {
    }

    public function add(string $middleware, ...$params): void
    {
        $this->chain->enqueue([$middleware, $params]);
    }

    public function process(HttpContext $context): Response
    {
        if ($this->chain->isEmpty()) {
            return $context->response();
        }
        
        if (($mware = $this->getNext()) === null) {
            return $context->response();
        }

        //try {
        return (new $mware[0](...$mware[1]))->handle($context, $this->process(...));
        //} catch (\Exception $ex) {
          //  return $context->response();
        //}
    }

    private function getNext(): array
    {
        return $this->chain->dequeue();
    }
}