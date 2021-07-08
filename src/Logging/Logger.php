<?php

namespace App\Logging;

/**
 * Class Logger
 * @package App\Logging
 */
class Logger
{

    /**
     * @var string $_fileExt
     */
    private static string $_fileExt = '.log';

    /**
     * Log to a given level
     *
     * @param string $level
     * @param $value
     */
    private static function _log(string $level, $value)
    {
        $file = LOGS . DS . mb_strtolower($level) . self::$_fileExt;
        self::_writeContentToFile($file, $value, $level, file_exists($file));
    }

    /**
     * Write content to file
     *
     * @param string $file
     * @param $content
     * @param string $level
     * @param bool $append
     */
    private static function _writeContentToFile(string $file, $value, string $level, bool $append = true)
    {
        $flags = $append ? FILE_APPEND : 0;

        $data = '';
        if ($append) {
            $data = str_repeat(PHP_EOL, 4);
        }
        $data .= str_repeat('-', 10);
        $data .= ' ' . mb_strtoupper($level) . ' ';
        $data .= str_repeat('-', 10);
        $data .= PHP_EOL;
        $data .= print_r($value, true);

        file_put_contents($file, $data, $flags);
    }

    /**
     * Log an error
     *
     * @param $value
     */
    public static function error($value)
    {
        self::_log('debug', $value);
        self::_log('error', $value);
    }

    /**
     * Log for debugging
     *
     * @param $value
     */
    public static function debug($value)
    {
        self::_log('debug', $value);
    }
}
