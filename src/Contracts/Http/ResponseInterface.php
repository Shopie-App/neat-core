<?php

declare(strict_types=1);

namespace Neat\Contracts\Http;

use Neat\Http\ActionResult\ActionResult;

interface ResponseInterface
{
    public function setActionResult(ActionResult $actionResult): void;
    
    public function withHeader(string $key, string $value, bool $replace = true): void;

    public function output(): void;
}