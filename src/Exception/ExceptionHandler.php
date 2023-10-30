<?php

declare(strict_types=1);

namespace Neat\Exception;

use Closure;
use Neat\Contracts\Http\ResponseInterface;

use function Neat\Http\Status\InternalServerError;

class ExceptionHandler
{
    public function __construct(private ResponseInterface $httpResponse, private Closure $terminateApp)
    {
        set_exception_handler([$this, 'handle']);
    }

    public function handle(\Throwable $ex): void
    {
        $this->httpResponse->setActionResult(InternalServerError([
            'error' => $ex->getMessage()
        ]));

        $this->httpResponse->output();

        ($this->terminateApp)();
    }
}