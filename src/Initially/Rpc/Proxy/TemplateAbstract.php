<?php
namespace Initially\Rpc\Proxy;

abstract class TemplateAbstract
{

    /**
     * @var string
     */
    protected $classTemplate;

    /**
     * @var string
     */
    protected $methodTemplate;

    /**
     * Get class template
     *
     * @return string
     */
    public function getClassTemplate()
    {
        if (!isset($this->classTemplate)) {
            $this->classTemplate = include __DIR__ . "/template/class.php";
        }

        return $this->classTemplate;
    }

    /**
     * Get method template
     *
     * @return string
     */
    public function getMethodTemplate()
    {
        if (!isset($this->methodTemplate)) {
            $this->methodTemplate = $this->_getMethodTemplate();
        }

        return $this->methodTemplate;
    }

    /**
     * Get method template
     *
     * @return string
     */
    abstract protected function _getMethodTemplate();

}