<?php

namespace App\Core;

/**
 * Class Configure
 *
 * @package App\Core
 */
class Configure
{

    /**
     * @var array $_config
     */
    private static array $_config = [];

    /**
     * @var string $_fileExt
     */
    private static string $_fileExt = '.php';

    /**
     * Initialize configuration
     */
    public static function initialize()
    {
        $configPath = CONFIG . DS . 'app' . self::$_fileExt;

        if (file_exists($configPath)) {
            self::$_config = include($configPath);
        }
    }

    /**
     * Write value to config
     *
     * @param string $key
     * @param $value
     */
    public static function write(string $key, $value)
    {
        self::$_config[$key] = $value;
    }

    /**
     * Read key
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public static function read(string $key, $default = null)
    {
        $result = self::$_config;
        foreach (explode('.', $key) as $recursiveKey) {
            if (isset($result[$recursiveKey])) {
                $result = $result[$recursiveKey];
            } else {
                $result = $default;
                break;
            }
        }

        return $result;
    }
}
