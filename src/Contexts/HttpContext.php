<?php

declare(strict_types=1);

namespace Neat\Contexts;

use Neat\Contracts\Http\RequestInterface;
use Neat\Contracts\Http\ResponseInterface;

class HttpContext
{
    public function __construct(
        private RequestInterface $request,
        private ResponseInterface $response
    ) {
    }

    /**
     * Http request object getter.
     * 
     * @return RequestInterface
     */
    public function request(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Http response object getter.
     * 
     * @return ResponseInterface
     */
    public function response(): ResponseInterface
    {
        return $this->response;
    }
}