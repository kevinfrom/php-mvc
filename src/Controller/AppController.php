<?php

namespace App\Controller;

use App\ORM\Model\MissingModelException;
use App\ORM\Model\ModelFactory;
use App\Request\RequestInterface;

/**
 * Class AppController
 * @package App\Controller
 */
class AppController implements ControllerInterface
{

    /**
     * @var RequestInterface|null $_request
     */
    private ?RequestInterface $_request;

    /**
     * @inheritDoc
     */
    public function __construct(RequestInterface $request)
    {
        $this->_request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): RequestInterface
    {
        return $this->_request;
    }

    /**
     * @inheritDoc
     */
    public function loadModel(string $model)
    {
        $class = ModelFactory::getModel($model);

        if (empty($class)) {
            throw new MissingModelException("Model $model does not exist.");
        }

        $this->{$model} = ModelFactory::getModel($model);
    }
}
