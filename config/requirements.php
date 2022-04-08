<?php

$REQUIRED_PHP_VERSION = '8.1.0';
if (version_compare(PHP_VERSION, $REQUIRED_PHP_VERSION) < 0) {
    trigger_error("Your PHP version must be equal or higher than $REQUIRED_PHP_VERSION.\n", E_USER_ERROR);
}

$requiredExtensions = ['pdo_mysql', 'intl', 'json', 'mbstring'];

foreach ($requiredExtensions as $extension) {
    if (extension_loaded($extension) === false) {
        trigger_error("You must enable the $extension extension." . PHP_EOL, E_USER_ERROR);
    }
}
