<?php

namespace App\Application;

use App\Configuration\ConfigurationException;
use App\Configuration\Configure;
use App\ORM\Connection\Connection;
use App\Routing\Router;
use App\Traits\SingletonTrait;

class Application
{

    use SingletonTrait;

    /**
     * Bootstrap the application
     *
     * @return void
     * @throws ConfigurationException
     */
    public function bootstrap(): void
    {
        $this->initConfiguration();
        $this->initDateTime();
        $this->initConnection();

        if (isCli() === false) {
            Router::getInstance()->handleRouting();
        }
    }

    /**
     * Initialize application configuration
     *
     * @return void
     * @throws ConfigurationException
     */
    private function initConfiguration(): void
    {
        Configure::initialize();
        $errorLevel = Configure::read('debug')
            ? Configure::read('Log.debugErrorLevel')
            : Configure::read('Log.errorLevel');
        ini_set('error_reporting', $errorLevel);
    }

    /**
     * Initialize DateTime timezones and locales
     *
     * @return void
     */
    private function initDateTime(): void
    {
        date_default_timezone_set(Configure::read('App.defaultTimezone'));
        ini_set('intl.default_locale', Configure::read('App.defaultLocale'));
    }

    /**
     * Initialize database connection
     *
     * @return void
     */
    private function initConnection(): void
    {
        Connection::getInstance()->initialize(Configure::read('Database'));
    }
}
