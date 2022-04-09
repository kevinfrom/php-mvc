<?php

namespace App\Controller;

use App\Request\RequestInterface;
use App\View\AppView;

/**
 * Interface ControllerInterface
 * @package App\Controller
 */
interface ControllerInterface
{
    /**
     * ControllerInterface constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request);

    /**
     * Get request
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface;

    /**
     * Get AppView
     *
     * @return AppView
     */
    public function getView(): AppView;

    /**
     * Load model
     *
     * @param string $model
     *
     * @return void
     */
    public function loadModel(string $model);
}
