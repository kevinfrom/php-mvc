<?php

namespace App\Debug\Type;

/**
 * Class DebugInformationNull
 *
 * @package App\Debug\Type
 */
class DebugInformationNull implements DebugInformationInterface
{

    /**
     * @inheritDoc
     */
    public static function getDebugInformation($data, int $depth = 1): string
    {
        return 'NULL';
    }
}
