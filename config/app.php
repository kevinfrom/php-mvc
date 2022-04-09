<?php

return [
    'debug' => false,
    'cache' => true,

    'App' => [
        'defaultLocale'   => 'da_DK',
        'defaultTimezone' => 'Europe/Copenhagen',
        'encoding'        => 'UTF-8',
    ],

    'Database' => [
        'host'     => '',
        'username' => '',
        'password' => '',
        'database' => '',
    ],

    'Cache' => [
        'oneHour' => [
            'path'     => CACHE,
            'duration' => '+1 hours',
            'prefix'   => 'one_hour_',
        ],
    ],

    'Log' => [
        'ErrorLevel'      => E_ALL & ~E_DEPRECATED & ~E_STRICT,
        'debugErrorLevel' => E_ALL,
    ],
];
