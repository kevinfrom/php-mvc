<?php

namespace App\Installer;

/**
 * Class AppInstaller
 *
 * @package App\Installer
 */
class AppInstaller
{

    /**
     * The root directory of the app - the folder containing `vendor` and `src` folders
     *
     * @var string $rootDir
     */
    private static string $rootDir = '';

    /**
     * Initialize
     */
    private static function initialize()
    {
        define('DS', DIRECTORY_SEPARATOR);
        self::$rootDir = dirname(dirname(__DIR__));
        require_once(self::$rootDir . DS . 'config' . DS . 'functions.php');
    }

    /**
     * Print a formatted message to the console
     *
     * @param string $message
     * @param string $level
     * @param bool   $wrapInParentheses
     */
    private static function print(string $message, string $level = 'info', bool $wrapInParentheses = false): void
    {
        $level = mb_strtoupper($level);
        $output = $message;

        if ($wrapInParentheses) {
            $output = "($output)";
        } else {
            $output = "$level: $output";
        }

        echo $output . "\n";
    }

    /**
     * Throw an error exception
     *
     * @param string $message
     *
     * @throws \Exception
     */
    private static function error(string $message): void
    {
        throw new \Exception($message);
    }

    /**
     * Post install
     */
    public static function postInstall(): void
    {
        self::runAllTasks();
    }

    /**
     * Post update
     */
    public static function postUpdate(): void
    {
        self::runAllTasks();
    }

    /**
     * Run all tasks
     */
    private static function runAllTasks(): void
    {
        self::print('Running all tasks', 'info', true);

        self::initialize();
        self::copyConfigFile();

        self::print('Finished all tasks', 'info', true);
    }

    /**
     * Copy default config file is config does not exist
     *
     * @throws \Exception
     */
    private static function copyConfigFile(): void
    {
        self::print('Setting config file');
        $defaultConfigPath = self::$rootDir . DS . 'config' . DS . 'app.php';
        $configPath        = self::$rootDir . DS . 'config' . DS . 'app.local.php';

        if (file_exists(self::$rootDir . DS . 'config' . DS . 'app.local.php')) {
            return;
        }

        $success = copy($defaultConfigPath, $configPath);

        if ($success) {
            self::print('Copied config/app.local.php to config/app.php');
        } else {
            self::error('Failed to copy config/app.local.php to config/app.php');
        }
    }
}
