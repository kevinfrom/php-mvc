<?php

namespace App\Controller;

use App\Core\Configure;
use App\Logging\Logger;
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
        $debugMessage = ['The requested uri was not found', 'Request uri: ' . $_SERVER['REQUEST_URI']];
        if ($this->debugActive()) {
            Logger::error($debugMessage);
            http_response_code(500);
            throw new MissingViewException();
        }

        http_response_code(404);
        $this->getView()->setTemplate('404');
        Logger::debug($debugMessage);
    }

    /**
     * Returns if debug is active
     *
     * @return bool
     */
    private function debugActive(): bool
    {
        return (bool)Configure::read('debug');
    }

    /**
     * Render error 500 view
     *
     * @throws \App\Debug\DebugInformationException
     */
    public function error500(Throwable $exception): void
    {
        http_response_code(500);
        Logger::error($exception->getMessage());
        if ($this->debugActive()) {
            dd($exception);
        }

        $this->getView()->setTemplate('500');
    }
}
