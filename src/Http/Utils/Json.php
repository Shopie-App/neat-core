<?php

declare(strict_types=1);

namespace Neat\Http\Utils;

use Neat\Attributes\Json\Json as AttributeJson;
use ReflectionClass;
use ReflectionProperty;
use stdClass;
use Traversable;

class Json
{
    /**
     * Maps object or collection of same object type to key/value array.
     * 
     * Only private properties that are initialized will be added.
     */
    public static function marshal(object|array $objectOrArray): array
    {
        $json = [];
        
        if ($objectOrArray instanceof Traversable) {

            foreach ($objectOrArray as $object) {

                $arrSingle = [];

                self::marshalLoop($arrSingle, $object);

                $json[] = $arrSingle;
            }
        } else {

            self::marshalLoop($json, $objectOrArray);
        }

        return $json;
    }

    /**
     * Maps json input to object.
     * 
     * Properties with no Json attribute are not added.
     */
    public static function unMarshal(stdClass $json, object $object): void
    {
        self::unMarshalLoop($json, $object);
    }

    private static function marshalLoop(array &$json, object $object): void
    {
        $reflector = new ReflectionClass($object);

        $props = $reflector->getProperties();

        foreach ($props as $prop) {

            // skip null
            if (!$prop->isInitialized($object)) {
                continue;
            }

            // get json attribute
            $attrs = $prop->getAttributes(AttributeJson::class);

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

            // get type (supports only named types)
            $type = $prop->getType();

            // scalar type assign and go to next
            if ($type->isBuiltin()) {
                $json[$key] = $prop->getValue($object);
                continue;
            }

            // handle dates
            if ($type->getName() == 'DateTime') {
                $json[$key] = $prop->getValue($object)->format('Y-m-d H:i:s');
                continue;
            }

            $json[$key] = [];

            self::marshalLoop($json[$key], $prop->getValue($object));
        }
    }

    private static function unMarshalLoop(stdClass $json, object $object): void
    {
        $reflector = new ReflectionClass($object);

        foreach ($reflector->getProperties() as $prop) {

            // check if it's a traditional private property
            $isPrivate = $prop->isPrivate();
            
            // check if it's a public/protected property with a private(set)
            $isPrivateSet = false;
            
            if (method_exists($prop, 'isPrivateSet')) {
                $isPrivateSet = $prop->isPrivateSet();
            }

            // skip public-set or protected-set
            if (!$isPrivate && !$isPrivateSet) {
                continue;
            }

            // get json attributes
            $attrs = $prop->getAttributes(AttributeJson::class);

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

            // scalar types and stdClass assign and go to next
            if ($prop->getType()->isBuiltin() || $prop->getType()->getName() === 'stdClass') {

                if (isset($json->$key)) {
                    $prop->setValue($object, self::castValue($prop->getType()->getName(), $json->$key));
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
                self::unMarshalLoop($json->$key, $newObject);
            }
        }
    }

    /**
     * Cast a value to correct type.
     */
    private static function castValue(?string $type, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'int'    => is_numeric($value) ? (int) $value : $value,
            'float'  => is_numeric($value) ? (float) $value : $value,
            'bool'   => is_bool($value) ? $value : filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'string' => (string) $value,
            'array'  => (array) $value,
            default  => $value
        };
    }
}