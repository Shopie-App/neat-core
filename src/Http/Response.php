<?php

declare(strict_types=1);

namespace Neat\Http;

use Neat\Contracts\Http\ResponseInterface;
use Neat\Http\ActionResult\ActionResult;

final class Response implements ResponseInterface
{
    private ActionResult $actionResult;

    private array $headers;

    public function __construct()
    {
        $this->actionResult = new ActionResult();

        $this->headers = [];
    }

    public function setActionResult(ActionResult $actionResult): void
    {
        $this->actionResult = $actionResult;
    }

    public function withHeader(string $key, string $value, bool $replace = true): void
    {
        $this->headers[] = [$key.': '.$value, $replace];
    }

    public function output(): void
    {
        $this->actionResult->execute($this->headers);
    }
}