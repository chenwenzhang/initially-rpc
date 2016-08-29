<?php
namespace Initially\Rpc\Proxy;

use Initially\Rpc\Exception\InitiallyRpcException;
use ReflectionClass;
use ReflectionMethod;

abstract class BuilderAbstract
{

    /**
     * @var string
     */
    protected $proxyDir;

    /**
     * @var TemplateAbstract
     */
    protected $template;

    /**
     * @var string
     */
    protected $interface;

    /**
     * @var array
     */
    protected $useList = array();

    /**
     * @var ReflectionClass
     */
    protected $refectionClass;

    /**
     * @var string
     */
    protected $proxyPrefixNamespace = "Initially\\Rpc\\Api";

    /**
     * @var string
     */
    protected $classNameRule = "/^([A-Za-z_][A-Za-z0-9_]*)Interface$/";

    /**
     * BuilderAbstract constructor.
     */
    public function __construct()
    {
        $this->proxyDir = __DIR__ . "/../Api";
        $this->template = $this->getTemplate();
    }

    /**
     * @throws InitiallyRpcException
     */
    protected function proxyDirChecker()
    {
        if (!is_dir($this->proxyDir) && !@mkdir($this->proxyDir, 0777, true)) {
            throw new InitiallyRpcException("Proxy directory create failed");
        }
    }

    /**
     * Create proxy class
     *
     * @param string $interface
     */
    public function create($interface)
    {
        $this->clear();
        $this->interface = $interface;
        $this->reflectionInterfaceAndCheck();

    }

    /**
     * 获取模板类
     *
     * @return TemplateAbstract
     */
    abstract protected function getTemplate();

    /**
     * Clear
     */
    protected function clear()
    {
        $this->useList = array();
        unset($this->refectionClass);
        $this->interface = "";
    }

    /**
     * @throws InitiallyRpcException
     */
    protected function reflectionInterfaceAndCheck()
    {
        if (!preg_match($this->classNameRule, $this->interface)) {
            throw new InitiallyRpcException("interface name must ending of 'Interface' like 'DemoServiceInterface'");
        }

        $this->refectionClass = new ReflectionClass($this->interface);
        if (!$this->refectionClass->isInterface()) {
            throw new InitiallyRpcException("Client service must be interface");
        }
    }

}