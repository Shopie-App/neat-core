<?php

declare(strict_types=1);

namespace Neat\Contracts\Http\ActionResult;

interface ActionResultInterface
{
    public function execute(): void;
}