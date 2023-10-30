<?php

declare(strict_types=1);

namespace Neat\Contracts\Contexts;

use Neat\Contexts\HttpContext;

interface HttpContextBuilderInterface
{
    public function getResult(): HttpContext;
}