<?php
/**
 * Bootstrap application
 */

ini_set('error_reporting', E_ALL);
require_once 'config' . DIRECTORY_SEPARATOR . 'requirements.php';

use App\Application\Application;

require_once 'config' . DIRECTORY_SEPARATOR . 'paths.php';
require_once 'vendor' . DS . 'autoload.php';
require_once CONFIG . DS . 'functions.php';

Application::getInstance()->bootstrap();
