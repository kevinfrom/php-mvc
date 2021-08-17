<?php

namespace App\Controller;

use App\Request\RequestInterface;

/**
 * Class ListsController
 *
 * @property \App\ORM\Model\ListsModel $Lists
 *
 * @package App\Controller
 */
class ListsController extends AppController implements ControllerInterface
{

    /**
     * @inheritDoc
     * @throws \App\ORM\Model\MissingModelException
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request);

        $this->loadModel('Lists');
    }

    /**
     * Index
     */
    public function index()
    {

    }
}
