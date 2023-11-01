<?php

declare(strict_types=1);

namespace Neat\Contracts\Http\ActionResult;

interface ActionResultInterface
{
    /**
     * Execute the result.
     */
    public function execute(): void;
}