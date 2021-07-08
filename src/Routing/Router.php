<?php

namespace App\Routing;

use App\Logging\Logger;
use App\View\AppView;

/**
 * Class Router
 *
 * @package App\Routing
 */
class Router
{

    /**
     * All routes for the application
     *
     * @var array $_routes
     */
    private array $_routes = [
        '/' => 'index',
        '/index' => 'index',
        '/about' => 'about',
    ];

    /**
     * Router constructor.
     */
    public function __construct()
    {
    }

    /**
     * Handle routing
     *
     * @return void
     */
    public function handleRouting()
    {
        $requestedView = $_SERVER['REQUEST_URI'];
        $view = $this->_routes[$requestedView] ?? '404';
        $this->renderView($view);

        if ($view === '404') {
            http_response_code(404);
            $this->renderView(404);
            Logger::debug(['View was not found', 'Request URI: ' . $requestedView]);
        }
    }

    /**
     * Render error 500 views
     */
    public function renderError()
    {
        http_response_code(500);
        $this->renderView('500');
        Logger::error((array)'Internal error' + $_SERVER);
    }

    /**
     * Render view
     *
     * @param string $viewName
     *
     * @return void
     */
    private function renderView(string $viewName)
    {
        new AppView($viewName);
    }
}
