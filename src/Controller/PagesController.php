<?php

namespace App\Controller;


use App\View\AppView;

/**
 * Class PagesController
 * @package App\Controller
 */
class PagesController extends AppController implements ControllerInterface
{

    /**
     * @var string $_viewPath
     */
    private string $_viewPath = APP . DS . 'Template' . DS;

    /**
     * @var string $_fileExt
     */
    private string $_fileExt = '.php';

    /**
     * Find matching view otherwise render 404 view
     *
     * @param string $view
     */
    public function view(string $view = 'index')
    {
        new AppView($view);
    }

    /**
     * View exists
     *
     * @param string $view
     * @return bool
     */
    public function viewExists(string $view = 'index'): bool
    {
        if (empty($view)) {
            $view = 'index';
        }

        return file_exists($this->_viewPath . $view . $this->_fileExt);
    }
}
