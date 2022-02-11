<?php

if (version_compare(PHP_VERSION, '7.4.0') < 0) {
    trigger_error('Your PHP version must be equal or higher than 7.4.0.' . PHP_EOL, E_USER_ERROR);
}

$requiredExtensions = ['pdo_mysql', 'intl', 'json', 'mbstring'];

foreach ($requiredExtensions as $extension) {
    if (extension_loaded($extension) === false) {
        trigger_error("You must enable the $extension extension." . PHP_EOL, E_USER_ERROR);
    }
}
