<?php

namespace App\Debug\Type;

/**
 * Class DebugInformationResource
 *
 * @package App\Debug\Type
 */
class DebugInformationResource implements DebugInformationInterface
{

    /**
     * @inheritDoc
     */
    public static function getDebugInformation($data, int $depth = 1): string
    {
        return $data . ' (' . get_resource_type($data) . ')';
    }
}
