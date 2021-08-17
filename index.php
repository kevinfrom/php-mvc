<?php
/**
 * Bootstrap application
 */

ini_set('error_reporting', E_ALL);
require_once 'config' . DIRECTORY_SEPARATOR . 'requirements.php';

use App\Core\Configure;
use App\Routing\Router;
use App\ORM\Connection\Connection;


require_once 'config' . DIRECTORY_SEPARATOR . 'paths.php';
require_once 'vendor' . DS . 'autoload.php';
require_once CONFIG . DS . 'functions.php';


Configure::initialize();
$errorLevel = Configure::read('Log.errorLevel');
if (Configure::read('debug')) {
    $errorLevel = Configure::read('Log.debugErrorLevel');
}
ini_set('error_reporting', $errorLevel);
date_default_timezone_set(Configure::read('App.defaultTimezone'));
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

require_once CONFIG . DS . 'routes.php';

Connection::initialize(Configure::read('Database'));
Router::getInstance()->handleRouting();
