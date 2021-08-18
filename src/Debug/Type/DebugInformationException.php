<?php

namespace App\Debug\Type;

use App\Debug\Debugger;
use Error;

/**
 * Class DebugInformationException
 *
 * @package App\Debug\Type
 */
class DebugInformationException implements DebugInformationInterface
{

    /**
     * @param Error $data
     * @param int   $depth
     *
     * @return string
     */
    public static function getDebugInformation($data, int $depth = 1): string
    {
        $trace  = self::getFormattedTrace($data->getTrace());
        $end    = "\n" . str_repeat("\t", $depth - 1);
        $result = $data->getMessage();
        $result .= $end . Debugger::getDebugInformation(['trace' => $trace], $depth);

        return $result . $end;
    }

    /**
     * Get formatted trace
     *
     * @param array $trace
     *
     * @return array
     */
    private static function getFormattedTrace(array $trace): array
    {
        return array_map(function ($trace) {
            return [
                'called' => self::formatCalled($trace),
                'caller' => $trace['file'],
                'args' => $trace['args'],
            ];
        }, $trace);
    }

    /**
     * Format argument
     *
     * @param mixed $argument
     *
     * @return string
     */
    private static function formatArgument(mixed $argument): string
    {
        return match (gettype($argument)) {
            'string' => "\"$argument\"",
            'array' => '[' . implode(', ', $argument) . ']',
            'object' => get_class($argument),
            'NULL' => 'NULL',
            'boolean' => $argument ? 'TRUE' : 'FALSE',
            'resource', 'resource (closed)' => '(' . get_resource_type($argument) . ')',
            default => (string)$argument,
        };
    }

    /**
     * Format called
     *
     * @param array $trace
     *
     * @return string
     */
    private static function formatCalled(array $trace): string
    {
        $arguments = array_map(function ($arg) {
            return self::formatArgument($arg);
        }, $trace['args']);
        $arguments = implode(', ', $arguments);

        return $trace['class'] . '::' . $trace['function'] . "($arguments)";
    }
}
