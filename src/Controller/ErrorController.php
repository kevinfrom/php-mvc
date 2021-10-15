<?php

namespace App\Controller;

use App\Core\Configure;
use App\Logging\Logger;
use App\View\AppView;
use App\View\MissingViewException;
use Throwable;

/**
 * Class ErrorController
 *
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
     *
     * @throws \App\Debug\DebugInformationException
     */
    public function error500(Throwable $exception): void
    {
        Logger::error($exception->getMessage());
        if (Configure::read('debug')) {
            dd($exception);
        }

        new AppView('500');
    }
}
