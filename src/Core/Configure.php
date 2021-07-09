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
     * Initialize configuration
     */
    public static function initialize()
    {
        $configPath = CONFIG . DS . 'app.local.php';

        if (file_exists($configPath) === false) {
            throw new \Exception(str_replace(ROOT . DS, '', $configPath) . ' does not exist');
        }

        self::$_config = include($configPath);
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
        return extractKeyRecursively(self::$_config, $key, $default);
    }
}
