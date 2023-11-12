<?php

declare(strict_types=1);

namespace Neat\Http\Utils;

use Neat\Attributes\Json\Json as AttrJson;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

class Json
{
    /**
     * Maps object to json output. Only private properties that are
     * initialized will be added.
     */
    public static function fromObject(object $object): array
    {
        $json = [];
        
        self::fromObjectLoop($json, $object);

        return $json;
    }

    /**
     * Maps json input to object.
     */
    public static function toObject(stdClass $json, object $object): mixed
    {
        self::toObjectLoop($json, $object);

        return $object;
    }

    private static function fromObjectLoop(array &$json, object $object): void
    {
        $reflector = new ReflectionClass($object);

        $props = $reflector->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {

            // skip null
            if (!$prop->isInitialized($object)) {
                continue;
            }

            // get json attribute
            $attrs = $prop->getAttributes(AttrJson::class);

            // no attribute, set property name as key
            if (empty($attrs)) {

                $key = $prop->getName();
            } else {

                // get key name
                $key = $attrs[0]->newInstance()->key();

                // no value?
                if ($key == '') {

                    $key = $prop->getName();
                }
            }

            // scalar type assign and go to next
            if ($prop->getType()->isBuiltin()) {
                $json[$key] = $prop->getValue($object);
                continue;
            }

            $json[$key] = [];
            self::fromObjectLoop($json[$key], $prop->getValue($object));
        }
    }

    private static function toObjectLoop(stdClass $json, object $object): void
    {
        $reflector = new ReflectionClass($object);

        $props = $reflector->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {

            // get json attributes
            $attrs = $prop->getAttributes(AttrJson::class);

            // no json attribute, don't add
            if (empty($attrs)) {
                continue;
            }

            // get attribute arguments
            $args = $attrs[0]->getArguments();

            if (empty($args)) {

                $key = $prop->getName();
            } else {

                // get key name
                $key = $attrs[0]->newInstance()->key();

                // no value?
                if ($key == '') {

                    $key = $prop->getName();
                }
            }

            // scalar type assign and go to next
            if ($prop->getType()->isBuiltin()) {

                if (isset($json->$key)) {
                    $prop->setValue($object, $json->$key);
                }

                continue;
            }

            // init
            $newObject = new ($prop->getType()->getName())();

            // set its properties only if in json
            if (isset($json->$key)) {

                // set it
                $prop->setValue($object, $newObject);

                // add properties
                self::toObjectLoop($json->$key, $newObject);
            }
        }
    }
}