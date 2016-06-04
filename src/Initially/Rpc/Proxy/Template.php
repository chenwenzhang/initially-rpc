<?php
namespace Initially\Rpc\Proxy;

class Template
{

    /**
     * @var string
     */
    private $classTpl;

    /**
     * @var string
     */
    private $methodTpl;

    /**
     * @var string
     */
    private $methodTplForPHP7;

    /**
     * Template constructor.
     */
    public function __construct()
    {
        $this->_initClassTpl();
        $this->_initMethodTpl();
        $this->_initMethodTplForPHP7();
    }

    /**
     * @return string
     */
    public function getClassTpl()
    {
        return $this->classTpl;
    }

    /**
     * @return string
     */
    public function getMethodTpl()
    {
        return $this->methodTpl;
    }

    /**
     * @return string
     */
    public function getMethodTplForPHP7()
    {
        return $this->methodTplForPHP7;
    }

    /**
     * init class tpl
     */
    private function _initClassTpl()
    {
        $this->classTpl = <<< CLASSTPL
<?php
namespace __NAMESPACE__;

use Initially\Rpc\Protocol\InvokerHandler;
use Initially\Rpc\Protocol\Protocol;
__USE_LIST__
__CLASS_DOC__
class __CLASS_NAME__ implements __INTERFACE_NAME__
{

    /**
     * @var Protocol
     */
    private \$protocol;

    /**
     * @var InvokerHandler
     */
    private \$handle;

    /**
     * Construct
     */
    public function __construct()
    {
        \$this->protocol = new Protocol();
        \$this->handle = new InvokerHandler(\$this->protocol->refer("__INTERFACE_NAME_FORMAT__"));
    }
    __METHOD__
}
CLASSTPL;
    }

    /**
     * init method tpl
     */
    private function _initMethodTpl()
    {
        $this->methodTpl = <<< METHODTPL

    __METHOD_DOC__
    public function __METHOD__(__PARAMETER__)
    {
        return \$this->handle->invoke("__METHOD__", array(__ARGUMENT__));
    }

METHODTPL;
    }

    /**
     * init method tpl
     */
    private function _initMethodTplForPHP7()
    {
        $this->methodTpl = <<< METHODTPL

    __METHOD_DOC__
    public function __METHOD__(__PARAMETER__)__RETURN_TYPE__
    {
        return \$this->handle->invoke("__METHOD__", array(__ARGUMENT__));
    }

METHODTPL;
    }

}