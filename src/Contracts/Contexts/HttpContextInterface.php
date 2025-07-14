<?php

declare(strict_types=1);

namespace Neat\Contracts\Contexts;

use Neat\Contracts\Http\RequestInterface;
use Neat\Contracts\Http\ResponseInterface;

interface HttpContextInterface
{
    /**
     * Http request object getter.
     * 
     * @return RequestInterface
     */
    public function request(): RequestInterface;

    /**
     * Http response object getter.
     * 
     * @return ResponseInterface
     */
    public function response(): ResponseInterface;
}