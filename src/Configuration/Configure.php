<?php

namespace App\Configuration;

class Configure
{

    private static array $_config = [];

    /**
     * Initialize configuration
     *
     * @return void
     * @throws ConfigurationException
     */
    public static function initialize(): void
    {
        $configPath = CONFIG . DS . 'app.local.php';

        if (file_exists($configPath) === false) {
            throw new ConfigurationEXception(str_replace(ROOT . DS, '', $configPath) . ' does not exist');
        }

        self::$_config = include($configPath);
    }

    /**
     * Write value to config
     *
     * @param string $key
     * @param $value
     * @return void
     */
    public static function write(string $key, $value): void
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
    public static function read(string $key, mixed $default = null): mixed
    {
        return extractKeyRecursively(self::$_config, $key, $default);
    }
}
