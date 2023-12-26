<?php

declare(strict_types=1);

namespace Neat\Http\ActionResult;

use Neat\Http\Utils\Json;

final class JsonResult extends ActionResult
{
    public function __construct(
        public int $httpStatusCode = 200,
        public object|array|null $result = null,
        public string $contentType = 'application/json'
    ) {
    }

    public function execute(): void
    {
        if ($this->result != null) {
            
            echo json_encode($this->format(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }

    private function format(): object|array
    {
        if (is_array($this->result)) {

            return $this->result;
        }

        return Json::marshal($this->result);
    }
}