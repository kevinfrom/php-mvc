<?php

namespace App\Request;

use App\Controller\AppController;
use App\Controller\PagesController;
use App\Logging\Logger;
use Throwable;
use App\Controller\ControllerInterface;
use App\Controller\ErrorController;

/**
 * Class Request
 * @package App\Request
 */
class Request implements RequestInterface
{

    /**
     * Request parameters
     *
     * @var array $_params
     */
    private array $_params = [
        'controller' => '',
        'method' => '',
    ];

    /**
     * Query parameters
     *
     * @var array|string[] $_query
     */
    private array $_query = [];

    /**
     * POST data
     *
     * @var array|string[]|string[][] $_data
     */
    private array $_data = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        try {
            $this->_parseRequestData();
            $this->_parseRequestQuery();
            $this->_parseRequestParams();
        } catch (Throwable $exception) {
            Logger::error($exception->getMessage());
            $this->_throwInternalError();
        }
    }

    /**
     * Parse request params
     */
    private function _parseRequestParams(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = preg_replace('/\/\?.+/', '', $uri);
        $uri = substr($uri, 1, strlen($uri));

        $params = explode('/', $uri);
        $params = array_filter($params);

        $pageExists = function (string $page): bool {
            $pagesController = new PagesController($this);
            return $pagesController->viewExists($page);
        };

        if (count($params) === 1) {
            $this->_params = [
                'controller' => 'Pages',
                'method' => 'view',
                $params[0]
            ];

            if ($pageExists($params[0]) === false) {
                $this->_params = [
                    'controller' => 'Error',
                    'method' => 'error404',
                ];
            }
        } elseif (empty($params)) {
            $this->_params = [
                'controller' => 'Pages',
                'method' => 'view',
            ];
        } else {
            foreach ($params as $param) {
                if (empty($this->getParam('controller'))) {
                    $this->_params['controller'] = $param;
                } elseif (empty($this->getParam('method'))) {
                    $this->_params['method'] = $param;
                } else {
                    break;
                }
            }
        }

        $controllerClass = $this->_getControllerClass();
        /**
         * @var ControllerInterface $controllerClass
         */
        $controllerClass = new $controllerClass($this);

        try {
            $method = $this->getParam('method');

            if (method_exists($controllerClass, $method)) {
                $arguments = array_filter($this->_params, function ($key) {
                    return is_string($key) === false;
                }, ARRAY_FILTER_USE_KEY);

                $controllerClass->{$method}(...$arguments);
            } else {
                $this->_throwNotFound();
            }
        } catch (Throwable $exception) {
            Logger::error($exception->getMessage());
            $this->_throwInternalError();
        }
    }

    /**
     * Throw not found exception
     */
    private function _throwNotFound(): void
    {
        $controller = new ErrorController($this);
        $controller->error404();
    }

    /**
     * Throw internal error
     */
    private function _throwInternalError(): void
    {
        $controller = new ErrorController($this);
        $controller->error500();
    }

    /**
     * Returns the fully-qualified class name of the controller
     *
     * @return string
     */
    private function _getControllerClass(): string
    {
        return '\\App\\Controller\\' . ucfirst($this->getParam('controller')) . 'Controller';
    }

    /**
     * Parse request POST data
     */
    private function _parseRequestData(): void
    {
        $this->_data = $_POST;
    }

    /**
     * Parse request GET query
     */
    private function _parseRequestQuery(): void
    {
        $this->_query = $_GET;
    }

    /**
     * @inheritDoc
     */
    public function getParam(string $key): ?string
    {
        return $this->_params[$key] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ?array
    {
        return $this->_params;
    }


    /**
     * @inheritDoc
     */
    public function getQuery(string $key, $default = null)
    {
        return extractKeyRecursively($this->_query, $key, $default);
    }

    /**
     * @inheritDoc
     */
    public function getData(string $key, $default = null)
    {
        return extractKeyRecursively($this->_data, $key, $default);
    }
}
