<?php

declare(strict_types=1);

namespace Neat\Http;

use Neat\Contracts\Http\ActionResult\ActionResultInterface;
use Neat\Contracts\Http\ResponseInterface;

final class Response implements ResponseInterface
{
    private ActionResultInterface $actionResult;

    private array $headers;

    public function __construct()
    {
        $this->headers = [];
    }

    public function setActionResult(ActionResultInterface $actionResult): void
    {
        $this->actionResult = $actionResult;
    }

    public function withHeader(string $key, string $value, bool $replace = true): void
    {
        $this->headers[] = [$key.': '.$value, $replace];
    }

    public function output(): void
    {
        // send status
        http_response_code($this->actionResult->httpStatusCode);

        // send headers
        $this->headers[] = ['Content-Type: '.$this->actionResult->contentType.'; charset=UTF-8', true];

        foreach ($this->headers as $header) {
            header($header[0], $header[1]);
        }

        // execute result
        $this->actionResult->execute();
    }
}