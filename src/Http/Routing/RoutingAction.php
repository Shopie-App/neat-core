<?php

declare(strict_types=1);

namespace Neat\Http\Routing;

use Neat\Attributes\Http\RequestSource\FromBody;
use Neat\Attributes\Http\RequestSource\FromPost;
use Neat\Attributes\Http\RequestSource\FromQuery;
use Neat\Contexts\AppContext;
use Neat\Contracts\Http\RequestInterface;
use Neat\Contracts\Http\Routing\RoutingActionInterface;
use Neat\Http\ActionResult\ActionResult;
use ReflectionNamedType;

class RoutingAction implements RoutingActionInterface
{
    private RoutingInfo $routeInfo;

    public function __construct(
        private RequestInterface $httpRequest,
        private AppContext $context
    )
    {
    }

    public function setRouteInfo(RoutingInfo $routeInfo): void
    {
        $this->routeInfo = $routeInfo;
    }

    public function run(): ActionResult
    {
        // get action parameters
        $this->routeInfo->setActionParameters($this->extractParameters());

        // get constructor
        $constructor = $this->routeInfo->reflectedController()->getConstructor();

        // if no constructor, run action
        if ($constructor === null) {
            return $this->routeInfo->reflectedController()->getMethod($this->routeInfo->actionName())
            ->invoke($this->routeInfo->reflectedController()->newInstanceWithoutConstructor(),
            ...$this->routeInfo->actionParameters());
        }

        // init controller dependencies
        $constructorParameters = $this->loadDependencies($constructor);

        // freemem
        $constructor = null;

        // run action
        return ($this->routeInfo->reflectedController()->newInstance(...$constructorParameters))
        ->{$this->routeInfo->actionName()}(...$this->routeInfo->actionParameters());
    }

    public function extractParameters(): array
    {
        // get action parameters
        $methodParams = $this->routeInfo->reflectedController()->getMethod($this->routeInfo->actionName())->getParameters();

        // have parameters
        if (empty($methodParams)) {
            return [];
        }

        // get from path or load object if it has attribute
        $pathParams = $this->routeInfo->parameters();
        $params = [];
        foreach ($methodParams as $param) {

            // type
            /** @var ReflectionNamedType $type */
            $type = $param->getType();

            // get attributes if any
            $attrs = $param->getAttributes();

            // no attributes, set from path parts
            if (empty($attrs)) {

                // add casted value
                $params[] = isset($pathParams[0]) ? $this->cast($type->getName(), $pathParams[0]): null;

                // remove from array checked index
                if (!empty($pathParams)) {
                    array_shift($pathParams);
                }

                // next
                continue;
            }

            // load object according to attribute
            // currently supporting from query, from post and from body
            // $attrs[0]->getName() === FromQuery::class || $attrs[0]->getName() === FromPost::class
            if (in_array($attrs[0]->getName(), [FromQuery::class, FromPost::class])) {
                
                $params[] = $attrs[0]->newInstance()->loadObject($param->getName(), $this->httpRequest);

            } else if ($attrs[0]->getName() === FromBody::class) {

                $params[] = $attrs[0]->newInstance()->loadObject($type->getName(), $this->httpRequest);
            }
        }

        return $params;
    }

    public function loadDependencies(\ReflectionMethod $constructor): array
    {

        // get constructor parameters
        $params = $constructor->getParameters();

        // have parameters?
        if (empty($params)) {
            return [];
        }

        $deps = [];
        foreach ($params as $param) {
            $deps[] = !$param->getType()->isBuiltin() && !$param->isDefaultValueAvailable() ? $this->context->provider()->getService($param->getType()->getName()): $param->getDefaultValue();
        }

        return $deps;
    }

    private function cast(string $type, mixed $value): mixed
    {
        $newValue = null;

        // TODO: safe escape string
        switch ($type) {
            case 'int': $newValue = (int) $value;
                break;
            default: $newValue = strval($value);
        }

        return $newValue;
    }
}