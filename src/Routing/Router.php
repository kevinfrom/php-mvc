<?php

namespace App\Routing;

use App\Controller\AppController;
use App\Controller\ControllerInterface;
use App\Controller\ErrorController;
use App\Logging\Logger;
use App\Request\Request;
use Throwable;

/**
 * Class Router
 *
 * @package App\Routing
 */
class Router
{

    /**
     * @var Request|null $_request
     */
    private ?Request $_request;

    /**
     * @var Router|null $_instance
     */
    private static ?Router $_instance;

    /**
     * @var array|string[]
     */
    private array $_connectedRoutes = [];

    /**
     * Router constructor.
     */
    private function __construct()
    {
        $this->_request = new Request();
    }

    /**
     * Handle routing
     */
    public function handleRouting(): void
    {
        $connectedRoute = $this->_getConnectedRoute();
        if ($connectedRoute) {
            foreach ($connectedRoute as $param => $value) {
                $this->getRequest()->setParam($param, $value);
            }
        }

        $controllerClass = $this->_getControllerClass();
        /**
         * @var ControllerInterface $controllerClass
         */
        $controllerClass = new $controllerClass($this->getRequest());

        try {
            $method = $this->getRequest()->getParam('method');

            if (method_exists($controllerClass, $method)) {
                $arguments = array_filter($this->getRequest()->getParams(), function ($key) {
                    return is_string($key) === false;
                }, ARRAY_FILTER_USE_KEY);

                $controllerClass->{$method}(...$arguments);
            } else {
                $this->_throwNotFound();
            }
        } catch (Throwable $exception) {
            $this->_throwInternalError($exception);
        }
    }

    /**
     * Returns the fully-qualified class name of the controller
     *
     * @return string
     */
    private function _getControllerClass(): string
    {
        $controller = $this->getRequest()->getParam('controller');

        return '\\App\\Controller\\' . ucfirst($controller) . 'Controller';
    }

    /**
     * Get connected route
     *
     * @return array|null
     */
    public function _getConnectedRoute(): ?array
    {
        return $this->_connectedRoutes[$this->getRequest()->getPath()] ?? null;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->_request;
    }

    /**
     * @return Router
     */
    public static function getInstance(): Router
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Throw not found exception
     */
    private function _throwNotFound(): void
    {
        $controller = new ErrorController($this->getRequest());
        $controller->error404();
    }

    /**
     * Throw internal error
     *
     * @param Throwable $exception
     */
    private function _throwInternalError(Throwable $exception): void
    {
        $controller = new ErrorController($this->getRequest());
        $controller->error500($exception);
    }

    /**
     * Connect a route to a controller method
     *
     * @param string         $uri
     * @param array|string[] $params
     */
    public function connect(string $uri, array $params): void
    {
        $this->_connectedRoutes[$uri] = $params;
    }
}
