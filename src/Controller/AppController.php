<?php

namespace App\Controller;

use App\ORM\Model\MissingModelException;
use App\ORM\Model\ModelFactory;
use App\Request\RequestInterface;
use App\View\AppView;

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
     * @var AppView $_view
     */
    private AppView $_view;

    /**
     * @inheritDoc
     */
    public function __construct(RequestInterface $request)
    {
        $this->_request = $request;
        $this->_view = new AppView($request->getParam('method'));
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
    public function getView(): AppView
    {
        return $this->_view;
    }

    /**
     * @inheritDoc
     */
    public function loadModel(string $model)
    {
        $class = ModelFactory::getModel($model);

        if ($class === null) {
            throw new MissingModelException("Model $model does not exist.");
        }

        $this->{$model} = ModelFactory::getModel($model);
    }
}
