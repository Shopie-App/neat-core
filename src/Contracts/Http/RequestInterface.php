<?php

declare(strict_types=1);

namespace Neat\Contracts\Http;

use stdClass;

interface RequestInterface
{
    public function host(): string;

    public function uri(): string;

    public function method(): string;

    public function get(): stdClass;

    public function post(): stdClass;

    public function args(): stdClass;

    public function body(): stdClass;

    public function header(string $key): string|null;

    public function cookie(string $key): string|null;

    public function setArgs(stdClass $args): void;

    public function loadHost(): void;

    public function loadUriPath(): void;

    public function loadMethod(): void;

    public function loadBody(): void;
}