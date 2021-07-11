<?php

use App\Routing\Router;

Router::getInstance()->connect('/', [
    'controller' => 'Lists',
    'method' => 'index',
]);
