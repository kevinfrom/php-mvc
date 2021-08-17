<?php

namespace App\Debug\Type;

/**
 * Class DebugInformationString
 *
 * @package App\Debug\Type
 */
class DebugInformationString implements DebugInformationInterface
{

    /**
     * @inheritDoc
     */
    public static function getDebugInformation($data, int $depth = 1): string
    {
        $isCli = php_sapi_name() === 'cli';
        return $isCli ? (string)$data : htmlentities($data, ENT_SUBSTITUTE);
    }
}
