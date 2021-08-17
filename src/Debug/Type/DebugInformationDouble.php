<?php

namespace App\Debug\Type;

/**
 * Class DebugInformationDouble
 *
 * @package App\Debug\Type
 */
class DebugInformationDouble implements DebugInformationInterface
{

    /**
     * @inheritDoc
     */
    public static function getDebugInformation($data, int $depth = 1): string
    {
        return (string)$data;
    }
}
