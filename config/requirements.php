<?php

use App\Logging\Logger;

$REQUIRED_PHP_VERSION = '8.1.0';
if (version_compare(PHP_VERSION, $REQUIRED_PHP_VERSION) < 0) {
    Logger::error('Installed PHP version ' . PHP_VERSION . ' is not great enough for ' . $REQUIRED_PHP_VERSION);
    trigger_error("Your PHP version must be equal or higher than $REQUIRED_PHP_VERSION.\n", E_USER_ERROR);
}

$requiredExtensions = ['pdo_mysql', 'intl', 'json', 'mbstring'];

foreach ($requiredExtensions as $extension) {
    if (extension_loaded($extension) === false) {
        Logger::error("Required extension $extension is not enabled");
        trigger_error("You must enable the $extension extension." . PHP_EOL, E_USER_ERROR);
    }
}
