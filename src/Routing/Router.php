<?php

namespace App\Routing;

use App\Controller\ControllerInterface;
use App\Controller\ErrorController;
use App\Request\Request;
use App\Traits\SingletonTrait;
use Throwable;

/**
 * @method static Router getInstance
 */
class Router
{

    use SingletonTrait;

    /**
     * @var Request|null $_request
     */
    private ?Request $_request;

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
                $arguments = array_filter($this->getRequest()->getParams(), static function ($key) {
                    return is_string($key) === false;
                }, ARRAY_FILTER_USE_KEY);

                $controllerClass->{$method}(...$arguments);
                $controllerClass->getView()->render();
            } else {
                $this->_throwNotFound();
            }
        } catch (Throwable $exception) {
            $this->_throwInternalError($exception);
        }
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
     * Throw not found exception
     */
    private function _throwNotFound(): void
    {
        $controller = new ErrorController($this->getRequest());
        $controller->error404();
        $controller->getView()->render();
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
        $controller->getView()->render();
    }

    /**
     * Connect a route to a controller method
     *
     * @param string $uri
     * @param array|string[] $params
     */
    public function connect(string $uri, array $params): void
    {
        $this->_connectedRoutes[$uri] = $params;
    }
}
