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
        $break  = "\n" . str_repeat("\t", $depth);
        $end    = "\n" . str_repeat("\t", $depth - 1);

        $values = [];
        foreach ($data as $key => $value) {
            if ($key === 'GLOBALS' && is_array($value) && isset($value['GLOBALS'])) {
                $value = '[recursion]';
            } elseif ($value !== $data) {
                $value = Debugger::getDebugInformation($value, $depth + 1);
            }

            $values[] = $break . Debugger::getDebugInformation($key, $depth + 1) . ' => ' . $value;
        }

        $result .= implode(', ', $values);
        $result .= $values ? $end : null;

        return $result . ']';
    }
}
