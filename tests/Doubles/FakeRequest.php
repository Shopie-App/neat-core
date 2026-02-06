<?php

declare(strict_types=1);

namespace Neat\Tests\Doubles;

use Neat\Http\Request;
use stdClass;

class FakeRequest extends Request
{
    public stdClass $get;

    public stdClass $body;

    public function __construct($get, $body = new stdClass)
    {
        $this->get = $get;

        $this->body = $body;
    }

    public function get(): stdClass
    {
        return $this->get;
    }

    public function body(): stdClass
    {
        return $this->body;
    }
}