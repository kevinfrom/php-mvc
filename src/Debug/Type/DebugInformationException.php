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
        $end    = "\n" . str_repeat("\t", $depth - 1);
        $result = $data->getMessage();
        $result .= $end . Debugger::getDebugInformation($data->getTrace(), $depth);

        return $result . $end;
    }
}
