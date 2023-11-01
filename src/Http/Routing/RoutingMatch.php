<?php

declare(strict_types=1);

namespace Neat\Http\Routing;

use Neat\Attributes\Http\HttpDelete;
use Neat\Attributes\Http\HttpGet;
use Neat\Attributes\Http\HttpPost;
use Neat\Attributes\Http\HttpPut;
use Neat\Contracts\Http\Routing\RoutingMatchInterface;
use ReflectionClass;
use ReflectionMethod;

class RoutingMatch implements RoutingMatchInterface
{
    private array $pathParts;

    private array $controllers = [];

    private ReflectionClass $reflector;

    private RoutingInfo $routeInfo;
    
    public function __construct()
    {
        $this->pathParts = [];
    }

    public function setRouteInfo(RoutingInfo $routeInfo): void
    {
        $this->routeInfo = $routeInfo;
    }

    public function setPathParts(array $pathParts): void
    {
        $this->pathParts = $pathParts;
    }

    public function setControllers(array $controllers): void
    {
        $this->controllers = $controllers;
    }

    public function match(): bool
    {
        // get controller name
        $this->routeInfo->setControllerName($this->extractControllerName());

        // remove controller name from array
        array_shift($this->pathParts);
        
        // init controller introspector
        $this->reflector = new ReflectionClass($this->routeInfo->controllerName());

        // set reflected class
        $this->routeInfo->setReflectedController($this->reflector);

        // get action name and parameters
        $this->routeInfo->setActionName($this->extractActionNameAndParameters());

        // controller and action valid?
        if (!$this->routeInfo->isValid()) {
            return false;
        }

        // done
        return true;
    }

    /**
     * Gets fully qualified controller name
     */
    public function extractControllerName(): string
    {
        // first part is controller name
        // if no value, send to index controller
        if ($this->pathParts[0] == '') {
            
            $nsClass = 'IndexController';
        } else {

            $nsClass = ucfirst(strtolower($this->pathParts[0])).'Controller';
        }

        // find in array
        foreach ($this->controllers as $controller) {

            if (str_ends_with($controller, '\\'.$nsClass)) {
                
                $nsClass = $controller;
            }
        }

        return $nsClass;
    }

    /**
     * Gets action name and parameters from path parts
     */
    public function extractActionNameAndParameters(): string
    {
        // get all public methods
        $methods = $this->reflector->getMethods(ReflectionMethod::IS_PUBLIC);
        
        // verb attribute to match
        if (($attributeClass = $this->getVerbAttribute($this->routeInfo->httpVerb())) == null) {
            return '';
        }

        // loop and get verb matching methods
        $matchedMethodsVerb = [];
        foreach ($methods as $method) {

            // http verb attribute only
            $attrs = $method->getAttributes($attributeClass);

            if (empty($attrs)) {
                continue;
            }

            // get argument, is the path
            $args = $attrs[0]->getArguments();

            $matchedMethodsVerb[] = [
                'name' => $method->getName(),
                'path' => isset($args[0]) ? $args[0] : ''
            ];
        }

        // length of path parts array (we cut on matched length also)
        $ppLen = count($this->pathParts);

        // loop verb matched methods and get path matched one
        $action = '';
        foreach ($matchedMethodsVerb as $method) {

            // break method's path to parts
            $pathPartsMethod = explode('/', $method['path']);
            
            // remove first empty element
            if ($pathPartsMethod[0] == '') {
                array_shift($pathPartsMethod);
            }

            // if arguments and path parts length don't match, go to next
            if ($ppLen != count($pathPartsMethod)) {
                continue;
            }

            // loop path parts and match method path parts
            $matchCount = 0;
            for ($i = 0; $i < $ppLen; $i++) {

                // parameter matching
                if ($this->isParameter($pathPartsMethod[$i]) && $this->parameterMatching($pathPartsMethod[$i], $this->pathParts[$i])) {

                    // add to parameters
                    $this->routeInfo->addParameter($this->pathParts[$i]);
                    
                    $matchCount++;
                    continue;
                }

                // string matching
                if ($pathPartsMethod[$i] === $this->pathParts[$i]) {
                    $matchCount++;
                }

            }

            // set first matched, should only by one anyway
            if ($matchCount == $ppLen) {
                $action = $method['name'];
                break;
            }
        }

        // freemem
        $matchedMethodsVerb = null;

        return $action;
    }

    public function parameterMatching(string $param, string $pathPart): bool
    {
        // get pathPart value type
        $valType = $this->getType($pathPart);

        // if no constraint matches string
        if (strpos($param, ':') === false) {
            return true;
        }

        // get param template
        $paramTemplate = str_replace('{', '', str_replace('}', '', $param));

        // template param and type
        $parts = explode(':', $paramTemplate);

        // currently supporting only int
        if ($parts[1] !== $valType) {
            return false;
        }

        return true;
    }

    private function getVerbAttribute(string $verb): mixed
    {
        $attr = null;

        switch ($verb) {
            case 'GET': $attr = HttpGet::class;
                break;
            case 'POST': $attr = HttpPost::class;
                break;
            case 'PUT': $attr = HttpPut::class;
                break;
            case 'DELETE': $attr = HttpDelete::class;
                break;
        }

        return $attr;
    }

    private function isParameter(string $param): bool
    {
        return strpos($param, '{') !== false ? true : false;
    }

    private function getType(string $value): string
    {
        return ctype_digit($value) ? 'int' : 'string';
    }
}