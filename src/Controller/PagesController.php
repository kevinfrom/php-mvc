<?php

namespace App\Controller;


/**
 * Class PagesController
 * @package App\Controller
 */
class PagesController extends AppController implements ControllerInterface
{

    /**
     * Find matching view otherwise render 404 view
     *
     * @param string $view
     */
    public function view(string $view = 'index'): void
    {
        $this->getView()->setTemplate($view);
    }
}
