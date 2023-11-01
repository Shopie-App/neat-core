<?php

declare(strict_types=1);

namespace Neat\Http\ActionResult;

use Neat\Contracts\Http\ActionResult\ActionResultInterface;

abstract class ActionResult implements ActionResultInterface
{
    abstract public function execute(): void;
}