<?php

namespace App\Controller;

use App\Logging\Logger;
use App\View\AppView;

/**
 * Class ErrorController
 * @package App\Controller
 */
class ErrorController extends AppController implements ControllerInterface
{

    /**
     * Render error 404 view
     */
    public function error404(): void
    {
        new AppView('404');
        Logger::debug(['The requested uri was not found', 'Request uri: ' . $_SERVER['REQUEST_URI']]);
    }

    /**
     * Render error 500 view
     */
    public function error500(): void
    {
        new AppView('500');
    }
}
