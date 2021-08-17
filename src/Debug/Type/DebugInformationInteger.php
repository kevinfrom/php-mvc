<?php

namespace App\Debug\Type;

/**
 * Class DebugInformationInteger
 *
 * @package App\Debug\Type
 */
class DebugInformationInteger implements DebugInformationInterface
{

    /**
     * @inheritDoc
     */
    public static function getDebugInformation($data, int $depth = 1): string
    {
        return (string)$data;
    }
}
