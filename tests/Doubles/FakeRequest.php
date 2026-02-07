<?php

declare(strict_types=1);

namespace Neat\Tests\Doubles;

use Neat\Http\Request;
use stdClass;

class FakeRequest extends Request
{
    public stdClass $get;

    public stdClass $body;

    public array $headers = [];

    public function __construct($get = null, $body = null)
    {
        $this->get = $get ?? new stdClass();

        $this->body = $body ?? new stdClass();
    }

    public function get(): stdClass
    {
        return $this->get;
    }

    public function body(): stdClass
    {
        return $this->body;
    }

    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function header(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }
}