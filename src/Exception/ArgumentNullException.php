<?php

declare(strict_types=1);

namespace Neat\Exception;

class ArgumentNullException extends \Exception
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous)
    {
        parent::__construct('Argument passed was null: '.$message, $code, $previous);
    }
}