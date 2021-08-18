<?php

namespace App\Debug;

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
            echo self::getCalledFrom() . $data . PHP_EOL;
        } else {
            echo sprintf(self::$outputStyles['called_from_format'], self::getCalledFrom());
            echo sprintf(self::$outputStyles['output_format'], $data);
        }
    }

    /**
     * Returns the file and line debug is called from
     *
     * @return string
     */
    public static function getCalledFrom(): string
    {
        $backTrace = debug_backtrace();
        $caller    = array_shift($backTrace);

        return $caller['file'] . ':' . $caller['line'];
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
        $dataType              = gettype($data);
        $dataType              = ucfirst(mb_strtolower($dataType));
        $debugInformationClass = "App\\Debug\\Type\\DebugInformation$dataType";

        if (class_exists($debugInformationClass) === false) {
            throw new DebugInformationException("Type $dataType DebugInformation class does not exist");
        }

        $result    = call_user_func([$debugInformationClass, 'getDebugInformation'], $data, $depth);
        $stylesKey = 'debug_' . mb_strtolower($dataType) . '_format';
        if (isset(self::$outputStyles[$stylesKey])) {
            $result = sprintf(self::$outputStyles[$stylesKey], $result);
        }

        return $result;
    }
}