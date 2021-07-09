<?php

namespace App\Controller;

use App\Request\RequestInterface;

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
}
