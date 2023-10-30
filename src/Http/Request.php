<?php

declare(strict_types=1);

namespace Neat\Http;

use Neat\Contracts\Http\RequestInterface;
use stdClass;

class Request implements RequestInterface
{
    private string $host;
    private string $uri;
    private string $method;
    private ?stdClass $get;
    private ?stdClass $post;
    private stdClass $args;
    private stdClass $body;

    public function __construct()
    {
        $this->loadHost();
        $this->loadUriPath();
        $this->loadMethod();
        $this->get = !empty($_GET) ? (object) $_GET : null;
        $this->post = !empty($_POST) ? (object) $_POST : null;
        $this->loadBody();
    }

    public function host(): string
    {
        return $this->host;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function get(): stdClass
    {
        return $this->get;
    }

    public function post(): stdClass
    {
        return $this->post;
    }

    public function args(): stdClass
    {
        return $this->args;
    }

    public function body(): stdClass
    {
        return $this->body;
    }

    public function header(string $key): string|null
    {
        return $_SERVER['HTTP_'.strtoupper(str_replace('-', '_', $key))] ?? null;
    }

    public function setArgs(stdClass $args): void
    {
        $this->args = $args;
    }

    public function loadHost(): void
    {
        if (!isset($_SERVER['HTTP_HOST'])) {
            $this->host = '';
            return;
        }

        if (($host = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_URL)) === false) {
            $this->host = '';
            return;
        }

        $this->host = $host;

        $host = null;
    }

    public function loadUriPath(): void
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            $this->uri = '';
            return;
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $this->uri = $path !== false ? $path : '';

        $path = null;
    }

    public function loadMethod(): void
    {
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
    }

    public function loadBody(): void
    {
        $stream = file_get_contents('php://input');

        if (($json = json_decode($stream, false)) == null) {
            $json = new stdClass();
        }

        $this->body = $json;

        $stream = null;
    }
}