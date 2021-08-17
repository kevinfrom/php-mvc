<?php

namespace App\Debug\Type;

use App\Debug\Debugger;
use ReflectionObject;
use ReflectionProperty;

/**
 * Class DebugInformationObject
 *
 * @package App\Debug\Type
 */
class DebugInformationObject implements DebugInformationInterface
{

    /**
     * @inheritDoc
     */
    public static function getDebugInformation($data, $depth = 1): string
    {
        $break = "\n" . str_repeat("\t", $depth);
        $end = "\n" . str_repeat("\t", $depth - 1);

        $result = 'object(' . get_class($data) . ') {';

        if (method_exists($data, '__debugInfo')) {
            $result .= str_repeat("\t", $depth) . ' ' . DebugInformationArray::getDebugInformation($data->__debugInfo());
        } else {
            $props = [];
            $reflectionObject = new ReflectionObject($data);
            $filters = [
                ReflectionProperty::IS_PUBLIC => 'public',
                ReflectionProperty::IS_STATIC => 'static',
                ReflectionProperty::IS_PROTECTED => 'protected',
                ReflectionProperty::IS_PRIVATE => 'private',
            ];

            foreach ($filters as $filter => $visibility) {
                $reflectionProperties = $reflectionObject->getProperties($filter);

                foreach ($reflectionProperties as $reflectionProperty) {
                    $reflectionProperty->setAccessible(true);
                    $property = $reflectionProperty->getValue($data);

                    $value = Debugger::getDebugInformation($property, $depth + 1);
                    $key = $reflectionProperty->getName();
                    $props[] = sprintf('[%s] %s => %s', $visibility, $key, $value);
                }
            }

            foreach (get_object_vars($data) as $key => $value) {
                $value = Debugger::getDebugInformation($value, $depth + 1);
                $props[] = "$key => $value";
            }

            $result .= $break . implode($break, $props) . $end;
        }

        return $result . '}';
    }
}
