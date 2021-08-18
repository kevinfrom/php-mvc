<?php

namespace App\Debug;

use Exception;

/**
 * Class Debugger
 */
class Debugger
{

    /**
     * @var array $outputStyles
     */
    private static array $outputStyles = [
        'output_format'        => '<pre style="margin-top: 0; padding: 5px; font-family: Consolas, monospace; font-weight: bold; font-size: 12px; color: #000000; display: block; z-index: 1000; overflow: scroll;">%s</pre>',
        'called_from_format'   => '<pre style="margin-bottom: 0; padding: 5px; font-family: Consolas, monospace; font-weight: normal; font-size: 12px; color: #2c2c2c; display: block; z-index: 1000;  overflow: scroll;">%s</pre>',
        'debug_null_format'    => '<span style="color: #B729D9;">%s</span>',
        'debug_boolean_format' => '<span style="color: #B729D9;">%s</span>',
        'debug_integer_format' => '<span style="color: #1299DA;">%s</span>',
        'debug_double_format'  => '<span style="color: #1299DA;">%s</span>',
        'debug_string_format'  => '<span style="color: #1299DA;">"</span>%s<span style="color: #1299DA;">"</span>',
    ];

    /**
     * @var string $calledFrom
     */
    private static string $calledFrom;

    /**
     * Debug data
     *
     * @param mixed $data
     *
     * @throws DebugInformationException
     */
    public static function debug($data)
    {
        self::output(self::getDebugInformation($data));
    }

    /**
     * Output a formatted debug
     *
     * @param $data
     */
    private static function output($data)
    {
        if (is_string($data) === false) {
            throw new DebuggerException('Debug data was not formatted correctly for debugger output');
        }

        if (isCli()) {
            echo str_pad(' DEBUG ', 100, '-', STR_PAD_BOTH) . PHP_EOL;
            echo self::$calledFrom . $data . PHP_EOL;
        } else {
            echo sprintf(self::$outputStyles['called_from_format'], self::$calledFrom);
            echo sprintf(self::$outputStyles['output_format'], $data);
        }
    }

    /**
     * Set called from
     *
     * @param bool $isException
     *
     * @param      $data
     */
    public static function setCalledFrom($data, bool $isException)
    {
        if ($isException) {
            self::$calledFrom = $data->getFile() . ':' . $data->getLine();
        } else {
            $backTrace        = debug_backtrace();
            $caller           = $backTrace[4];
            self::$calledFrom = $caller['file'] . ':' . $caller['line'];
        }
    }

    /**
     * Get debug information
     *
     * @param     $data
     * @param int $depth
     *
     * @return string
     * @throws DebugInformationException
     */
    public static function getDebugInformation($data, int $depth = 1)
    {
        $isException = gettype($data) === 'object' && $data instanceof Exception;
        if ($isException) {
            $dataType = 'Exception';
        } else {
            $dataType = gettype($data);
            $dataType = ucfirst(mb_strtolower($dataType));
        }

        $debugInformationClass = "App\\Debug\\Type\\DebugInformation$dataType";
        if (class_exists($debugInformationClass) === false) {
            throw new DebugInformationException("Type $dataType DebugInformation class does not exist");
        }

        if (empty(self::$calledFrom)) {
            self::setCalledFrom($data, $isException);
        }

        $result    = call_user_func([$debugInformationClass, 'getDebugInformation'], $data, $depth);
        $stylesKey = 'debug_' . mb_strtolower($dataType) . '_format';
        if (isset(self::$outputStyles[$stylesKey])) {
            $result = sprintf(self::$outputStyles[$stylesKey], $result);
        }

        return $result;
    }
}
