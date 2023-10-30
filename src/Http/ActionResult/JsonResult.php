<?php

declare(strict_types=1);

namespace Neat\Http\ActionResult;

class JsonResult extends ActionResult
{
    public function __construct(
        string $httpStatusCode = 200,
        string $httpStatusReason = 'OK',
        array $result = [],
        string $contentType = 'application/json'
    )
    {
        parent::__construct($httpStatusCode, $httpStatusReason, $result, $contentType);
    }
}