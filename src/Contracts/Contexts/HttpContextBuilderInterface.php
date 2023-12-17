<?php

declare(strict_types=1);

namespace Neat\Contracts\Contexts;

use Neat\Contexts\HttpContext;

interface HttpContextBuilderInterface
{
    /**
     * Builds the HTTP context.
     * 
     * @return HttpContext
     */
    public function getResult(): HttpContext;
}