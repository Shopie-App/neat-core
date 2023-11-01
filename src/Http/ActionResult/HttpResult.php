<?php

declare(strict_types=1);

namespace Neat\Http\ActionResult;

final class HttpResult extends ActionResult
{
    public function __construct(
        public int $httpStatusCode = 200,
        public ?string $result = null,
        public string $contentType = 'text/plain'
    ) {
    }

    public function execute(): void
    {
        if ($this->result != null) {
            
            echo $this->result;
        }
    }
}