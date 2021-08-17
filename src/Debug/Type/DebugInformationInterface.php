<?php

namespace App\Debug\Type;

/**
 * Interface DebugInformationInterface
 */
interface DebugInformationInterface
{

    /**
     * Get debug information for type
     *
     * @param mixed $data
     * @param int $depth
     *
     * @return string
     */
    public static function getDebugInformation($data, int $depth = 1): string;
}
