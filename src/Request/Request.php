<?php

namespace App\Request;

use App\Controller\PagesController;
use App\Core\Configure;

/**
 * Class Request
 *
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
        'method'     => '',
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
        $this->_parseRequestData();
        $this->_parseRequestQuery();
        $this->_parseRequestParams();
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

            return $pagesController->getView()->viewExists($page);
        };

        $controllerExists = function (string $controller): bool {
            $controller = ucfirst(mb_strtolower($controller));

            return class_exists('App\\Controller\\' . $controller . 'Controller');
        };

        if (count($params) === 1) {
            if ($controllerExists($params[0])) {
                $this->_params = [
                    'controller' => ucfirst(mb_strtolower($params[0])),
                    'method'     => 'index',
                ];
            } else {
                $this->_params = [
                    'controller' => 'Pages',
                    'method'     => 'view',
                    $params[0],
                ];

                if ($pageExists($params[0]) === false) {
                    $this->_params = [
                        'controller' => 'Error',
                        'method'     => Configure::read('debug') ? 'error500' : 'error404',
                    ];
                }
            }
        } elseif (empty($params)) {
            $this->_params = [
                'controller' => 'Pages',
                'method'     => 'view',
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
    public function setParam(string $key, string $value): void
    {
        if (isset($this->_params[$key])) {
            $this->_params[$key] = $value;
        }
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

    /**
     * @inheritDoc
     */
    public function getPath(): ?string
    {
        return $_SERVER['REQUEST_URI'] ?? null;
    }
}
