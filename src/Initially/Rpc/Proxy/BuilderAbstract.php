<?php
namespace Initially\Rpc\Proxy;

use Initially\Rpc\Core\Engine\Client as ClientApp;
use Initially\Rpc\Core\Engine\Config\Client as ClientConfig;
use Initially\Rpc\Core\Engine\Config\Service;
use Initially\Rpc\Core\Support\Util;
use Initially\Rpc\Exception\InitiallyRpcException;
use ReflectionClass;
use ReflectionMethod;

abstract class BuilderAbstract
{

    /**
     * Class template searches
     */
    const CLASS_TPL_SEARCHES = array(
        "__NAMESPACE__",
        "__USE_LIST__",
        "__CLASS_DOC__",
        "__CLASS_NAME__",
        "__INTERFACE_NAME__",
        "__INTERFACE_NAME_FORMAT__",
        "__METHOD__"
    );

    /**
     * @var ClientConfig
     */
    protected $config;

    /**
     * @var Service
     */
    protected $service;

    /**
     * @var TemplateAbstract
     */
    protected $template;

    /**
     * @var string
     */
    protected $interface = "";

    /**
     * @var array
     */
    protected $useList = array();

    /**
     * @var ReflectionClass
     */
    protected $refectionClass;

    /**
     * Interface suffix
     *
     * @var string
     */
    protected $interfaceEndOf = "Interface";

    /**
     * BuilderAbstract constructor.
     */
    public function __construct()
    {
        $this->config = ClientApp::getInstance()->getConfig();
        $this->template = $this->getTemplate();
    }

    /**
     * Create proxy class
     *
     * @param string $interface
     * @throws InitiallyRpcException
     */
    public function create($interface)
    {
        if (!Util::existsDirWritable($this->config->getProxyRootDir())) {
            throw new InitiallyRpcException("Proxy builder error: proxy class root dir not to be write");
        }

        $this->interface = $interface;
        $this->reflectionInterfaceAndCheck();
        $this->service = $this->config->getServiceByKey($this->interface);
        $info = $this->parseProxyClassInfo();
        $dir = $this->getProxyClassDirAndCreate($info->getNamespace());
        $file = sprintf("%s/%s.php", $dir, $info->getClassName());

        $methodString = "";
        foreach ($this->refectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodString .= $this->buildMethod($method);
        }

        $useString = "";
        $this->useList = array_unique($this->useList);
        foreach ($this->useList as $value) {
            $useString .= "use {$value};" . PHP_EOL;
        }

        $replaces = array(
            $info->getNamespace(),
            $useString,
            $this->refectionClass->getDocComment(),
            $info->getClassName(),
            sprintf("\\%s", $this->refectionClass->getName()),
            str_replace("\\", "\\\\", $this->refectionClass->getName()),
            $methodString
        );
        $content = str_replace(self::CLASS_TPL_SEARCHES, $replaces, $this->template->getClassTemplate());
        if (!@file_put_contents($file, $content)) {
            throw new InitiallyRpcException("Proxy builder error: proxy class code write failed");
        }

        $this->clear();
    }

    /**
     * @throws InitiallyRpcException
     */
    protected function reflectionInterfaceAndCheck()
    {
        $this->refectionClass = new ReflectionClass($this->interface);
        if (!$this->refectionClass->isInterface()) {
            throw new InitiallyRpcException("Proxy builder error: client service must be interface");
        }
    }

    /**
     * Parse interface info
     *
     * @return ProxyClassInfo
     * @throws InitiallyRpcException
     */
    protected function parseProxyClassInfo()
    {
        $arr = explode("\\", $this->interface);
        $name = array_pop($arr);
        $matches = array();
        if (!preg_match($this->getClassNameRule(), $name, $matches)) {
            throw new InitiallyRpcException("Proxy builder error: interface name must ending of '{$this->interfaceEndOf}' like 'DemoService{$this->interfaceEndOf}'");
        }

        $replace = $this->config->getReplace();
        $replaceKey = $this->service->getReplaceKey();
        if (!empty($arr) && !empty($replace) && isset($replace[$replaceKey])) {
            foreach ($arr as $key => $value) {
                if (isset($replace[$replaceKey][$value])) {
                    $arr[$key] = $replace[$replaceKey][$value];
                }
            }
        }

        $namespace = implode("\\", $arr);
        $info = new ProxyClassInfo();
        $info->setNamespace($namespace);
        $info->setClassName($matches[1]);

        return $info;
    }

    /**
     * Get proxy class dir and create it
     *
     * @param string $namespace
     * @return string
     * @throws InitiallyRpcException
     */
    protected function getProxyClassDirAndCreate($namespace)
    {
        $path = str_replace("\\", DIRECTORY_SEPARATOR, $namespace);
        $dir = $this->config->getProxyRootDir() . DIRECTORY_SEPARATOR . $path;
        if (!Util::createDirIfNotExists($dir)) {
            throw new InitiallyRpcException("Proxy builder error: get proxy class dir and create it");
        }

        return $dir;
    }

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
     * @return string
     */
    protected function getClassNameRule()
    {
        static $rule;
        if (isset($rule)) {
            return $rule;
        }

        $rule = sprintf("/^([A-Za-z_][A-Za-z0-9_]*)%s$/", $this->interfaceEndOf);
        return $rule;
    }

    /**
     * Build method string
     *
     * @param ReflectionMethod $method
     * @return string
     */
    abstract protected function buildMethod(ReflectionMethod $method);

    /**
     * Get template class
     *
     * @return TemplateAbstract
     */
    abstract protected function getTemplate();

}