<?php

namespace App\View;

use App\Logging\Logger;

/**
 * Class View
 *
 * @package App\View
 */
class AppView
{

    /**
     * @var string $_fileExt
     */
    private string $_fileExt = '.php';

    /**
     * @var string $_layoutsPath
     */
    private string $_layoutsPath = APP . DS . 'Layout' . DS;

    /**
     * @var string $_templatesPath
     */
    private string $_templatesPath = APP . DS . 'Template' . DS;

    /**
     * @var string $_elementsPath
     */
    private string $_elementsPath = APP . DS . 'Element' . DS;

    /**
     * @var array $_data
     */
    private array $_data = [];

    /**
     * @var array $_blocks
     */
    private array $_blocks = [];

    /**
     * @var string|null $_activeBlock
     */
    private ?string $_activeBlock = null;

    /**
     * @var string|null $_layoutFile
     */
    private ?string $_layoutFile = null;

    /**
     * @var null|string $_viewFile
     */
    private ?string $_viewFile = null;

    /**
     * View constructor.
     * @param string $template
     */
    public function __construct(string $template)
    {
        $this->_setLayout('default');
        $this->_setTemplate($template);
    }

    /**
     * Set layout
     *
     * @param string $layout
     */
    private function _setLayout(string $layout)
    {
        $this->_layoutFile = $this->_layoutsPath . $layout . $this->_fileExt;
    }

    private function _setTemplate(string $template)
    {
        $viewFile = $this->_templatesPath . $template . $this->_fileExt;

        if (file_exists($viewFile) === false) {
            $viewFile = $this->_templatesPath . '404' . $this->_fileExt;
            http_response_code(404);
            Logger::debug(['View was not found', 'Request URI: ' . $_SERVER['REQUEST_URI']]);
        }

        $this->_viewFile = $viewFile;
    }

    /**
     * Get rendered html
     *
     * @param string $includePath
     * @return string
     */
    private function _getRenderedHtml(string $includePath): string
    {
        extract($this->_data);
        ob_start();
        include($includePath);

        return (string)ob_get_clean();
    }

    /**
     * Assign a value for view template
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    protected function assign(string $key, $value)
    {
        $this->_data[$key] = $value;
    }

    /**
     * Fetch assigned View variable
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed|null
     */
    protected function fetch(string $key, $default = false)
    {
        return $this->_data[$key] ?? $default;
    }

    /**
     * Open and append to a block
     *
     * @param string $block
     */
    protected function append(string $block)
    {
        $this->_blocks[$block] = $this->fetch($block);
        $this->_activeBlock = $block;
        ob_start();
    }

    /**
     * End and render a block
     */
    protected function end()
    {
        if ($this->_activeBlock) {
            $this->_blocks[$this->_activeBlock] .= ob_get_clean();

            $this->assign($this->_activeBlock, $this->_blocks[$this->_activeBlock]);
            $this->_activeBlock = null;
            unset($this->_blocks[$this->_activeBlock]);
        }
    }

    /**
     * Render an element
     *
     * @param string $element
     *
     * @return string
     */
    protected function element(string $element): string
    {
        return $this->_getRenderedHtml($this->_elementsPath . $element . $this->_fileExt);
    }

    /**
     * Render view when destructed
     */
    public function __destruct()
    {
        $this->assign('content', $this->_getRenderedHtml($this->_viewFile));
        echo $this->_getRenderedHtml($this->_layoutFile);
    }
}
