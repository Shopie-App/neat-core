<?php

declare(strict_types=1);

namespace Neat\Contracts\Contexts;

use Neat\Contexts\AppContext;

interface AppContextBuilderInterface
{
    /**
     * Builds the application context.
     * 
     * @return AppContext
     */
    public function getResult(): AppContext;
}