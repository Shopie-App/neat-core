<?php

declare(strict_types=1);

namespace Neat\Http\ActionResult;

use Neat\Contracts\Http\ActionResult\ActionResultInterface;

final class ActionResult implements ActionResultInterface
{
    public function __construct(
        private int $httpStatusCode = 200,
        private string $httpStatusReason = 'OK',
        private mixed $result = null,
        private string $contentType = 'text/plain'
    )
    {
    }

    public function httpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function httpStatusReason(): string
    {
        return $this->httpStatusReason;
    }

    public function result(): mixed
    {
        return $this->result;
    }

    public function contentType(): string
    {
        return $this->contentType;
    }

    public function execute(array $headers = []): void
    {
        // start buffering
        ob_start();

        // get body output
        if ($this->result !== null) {

            if (is_string($this->result)) {

                echo $this->result;
            } else {

                $this->contentType = 'application/json';

                echo json_encode($this->result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }

        // get buffer contents
        $out = ob_get_contents();
        
        // end buffering
        ob_end_clean();

        // send status
        http_response_code($this->httpStatusCode);

        // send headers
        $headers[] = ['Content-Type: '.$this->contentType.'; charset=UTF-8', true];

        foreach ($headers as $header) {
            header($header[0], $header[1]);
        }

        // output body
        echo $out;
    }
}