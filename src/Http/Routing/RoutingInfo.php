<?php

declare(strict_types=1);

namespace Neat\Http\Routing;

use ReflectionClass;

class RoutingInfo
{
    private string $route;

    private string $httpVerb;

    private string $controllerName;

    private ReflectionClass $reflectedController;

    private string $actionName;

    private array $parameters;

    private array $actionParameters;

    public function __construct()
    {
        $this->parameters = [];
        $this->actionParameters = [];
    }

    public function route(): string
    {
        return $this->route;
    }

    public function httpVerb(): string
    {
        return $this->httpVerb;
    }

    public function controllerName(): string
    {
        return $this->controllerName;
    }

    public function reflectedController(): ReflectionClass
    {
        return $this->reflectedController;
    }

    public function actionName(): string
    {
        return $this->actionName;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function actionParameters(): array
    {
        return $this->actionParameters;
    }

    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    public function setHttpVerb(string $httpVerb): void
    {
        $this->httpVerb = $httpVerb;
    }

    public function setControllerName(string $controllerName): void
    {
        $this->controllerName = $controllerName;
    }

    public function setReflectedController(ReflectionClass $reflectedController): void
    {
        $this->reflectedController = $reflectedController;
    }

    public function setActionName(string $actionName): void
    {
        $this->actionName = $actionName;
    }

    public function addParameter(mixed $value): void
    {
        $this->parameters[] = $value;
    }

    public function setActionParameters(array $actionParameters): void
    {
        $this->actionParameters = $actionParameters;
    }

    public function isValid(): bool
    {
        if ($this->controllerName == '' || $this->actionName == '') {
            return false;
        }

        if (!class_exists($this->controllerName)) {
            return false;
        }

        return true;
    }
}