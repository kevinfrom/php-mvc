<?php

namespace App\Debug\Type;

use App\Debug\Debugger;

/**
 * Class DebugInformationArray
 *
 * @package App\Debug\Type
 */
class DebugInformationArray implements DebugInformationInterface
{

    /**
     * @inheritDoc
     */
    public static function getDebugInformation($data, int $depth = 1): string
    {
        $result = '[';
        $break = null;
        $end = null;

        $values = [];
        foreach ($data as $key => $value) {
            if ($key === 'GLOBALS' && is_array($value) && isset($value['GLOBALS'])) {
                $value = '[recursion]';
            } elseif ($value !== $data) {
                $value = Debugger::getDebugInformation($value);
            }

            $values[] = $break . Debugger::getDebugInformation($key) . ' => ' . $value;
        }

        return $result . implode(', ', $values) . $end . ']';
    }
}
