<?php

namespace App\Debug\Type;

/**
 * Class DebugInformationBoolean
 *
 * @package App\Debug\Type
 */
class DebugInformationBoolean implements DebugInformationInterface
{

    /**
     * @inheritDoc
     */
    public static function getDebugInformation($data, int $depth = 1): string
    {
        return $data ? 'TRUE' : 'FALSE';
    }
}
