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
        $this->setLayout('default');
        $this->setTemplate($template);
    }

    /**
     * Set layout
     *
     * @param string $layout
     * @return AppView
     */
    public function setLayout(string $layout)
    {
        $this->_layoutFile = $this->_layoutsPath . $layout . $this->_fileExt;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return AppView
     */
    public function setTemplate(string $template): self
    {
        $viewFile = $this->_templatesPath . $template . $this->_fileExt;

        if (file_exists($viewFile) === false) {
            $viewFile = $this->_templatesPath . '404' . $this->_fileExt;
            http_response_code(404);
            Logger::debug(['View was not found', 'Request URI: ' . $_SERVER['REQUEST_URI']]);
        }

        $this->_viewFile = $viewFile;

        return $this;
    }

    /**
     * @param string $view
     * @return bool
     */
    public function viewExists(string $view): bool
    {
        return file_exists(TEMPLATES . DS . $view . $this->_fileExt);
    }

    /**
     * Open and append to a block
     *
     * @param string $block
     */
    public function append(string $block)
    {
        $this->_blocks[$block] = $this->fetch($block);
        $this->_activeBlock = $block;
        ob_start();
    }

    /**
     * Fetch assigned View variable
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed|null
     */
    public function fetch(string $key, $default = false)
    {
        return $this->_data[$key] ?? $default;
    }

    /**
     * End and render a block
     */
    public function end()
    {
        if ($this->_activeBlock) {
            $this->_blocks[$this->_activeBlock] .= ob_get_clean();

            $this->assign($this->_activeBlock, $this->_blocks[$this->_activeBlock]);
            $this->_activeBlock = null;
            unset($this->_blocks[$this->_activeBlock]);
        }
    }

    /**
     * Assign a value for view template
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function assign(string $key, $value)
    {
        $this->_data[$key] = $value;
    }

    /**
     * Render an element
     *
     * @param string $element
     *
     * @return string
     */
    public function element(string $element): string
    {
        $elementPath = $this->_elementsPath . $element . $this->_fileExt;
        return $this->_getRenderedHtml($elementPath);
    }

    /**
     * Get rendered html
     *
     * @param string $includePath
     * @return string
     */
    private function _getRenderedHtml(string $includePath): string
    {
        extract($this->_data, EXTR_OVERWRITE);
        ob_start();
        include($includePath);

        return trim((string)ob_get_clean());
    }

    /**
     * Render view
     */
    public function render()
    {
        $this->assign('content', $this->_getRenderedHtml($this->_viewFile));
        echo $this->_getRenderedHtml($this->_layoutFile);
    }
}
