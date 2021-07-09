<?php

namespace App\Routing;

use App\Request\Request;

/**
 * Class Router
 *
 * @package App\Routing
 */
class Router
{

    /**
     * Router constructor.
     */
    public function __construct()
    {
        new Request();
    }
}
