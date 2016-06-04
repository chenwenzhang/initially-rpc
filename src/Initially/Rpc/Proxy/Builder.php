<?php
namespace Initially\Rpc\Proxy;

use Initially\Rpc\Core\Support\Constants;
use Initially\Rpc\Core\Support\Util;
use Initially\Rpc\Exception\InitiallyRpcException;
use ReflectionClass;
use ReflectionMethod;

class Builder
{

    /**
     * @var Template
     */
    private $template;

    /**
     * @var string
     */
    private $apiDir;

    /**
     * @var array
     */
    private $methodUseClasses = array();

    /**
     * @var string
     */
    private $classNameRule = "/^([A-Za-z_][A-Za-z0-9_]*)Interface$/";

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        $this->template = new Template();
        $this->apiDir = __DIR__ . "/../Api";
        $this->apiDirCheck();
    }

    /**
     * @throws InitiallyRpcException
     */
    private function apiDirCheck()
    {
        try {
            Util::createDirIfNotExists($this->apiDir);
        } catch (InitiallyRpcException $e) {
            throw new InitiallyRpcException("Proxy api directory create failed");
        }
    }

    /**
     * @param string $interface
     * @throws InitiallyRpcException
     */
    public function createProxy($interface)
    {
        $isPHP7 = version_compare(PHP_VERSION, "7.0.0", ">=");
        $this->methodUseClasses = array();
        $reflection = new ReflectionClass($interface);
        if (!$reflection->isInterface()) {
            throw new InitiallyRpcException("Client service must be interface");
        }

        $info = $this->parseProxyClassInfo($reflection->getName());
        $dir = $this->getProxyClassDirAndCreate($info["namespace"]);
        $file = $dir . "/{$info['classname']}.php";

        $methodString = "";
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methodString .= $isPHP7 ? $this->buildMethodStringForPHP7($method) : $this->buildMethodString($method);
        }

        $useString = "";
        foreach ($this->methodUseClasses as $value) {
            $useString .= "use {$value};\n";
        }

        $searches = array(
            "__NAMESPACE__",
            "__USE_LIST__",
            "__CLASS_DOC__",
            "__CLASS_NAME__",
            "__INTERFACE_NAME__",
            "__INTERFACE_NAME_FORMAT__",
            "__METHOD__");
        $replaces = array(
            $info["namespace"],
            $useString,
            $reflection->getDocComment(),
            $info["classname"],
            "\\{$reflection->getName()}",
            str_replace("\\", "\\\\", $reflection->getName()),
            $methodString
        );

        file_put_contents($file, str_replace($searches, $replaces, $this->template->getClassTpl()));
    }

    /**
     * @param ReflectionMethod $method
     * @return string
     */
    private function buildMethodString(ReflectionMethod $method) {
        $argumentString = "";
        $paramString = "";
        foreach ($method->getParameters() as $parameter) {
            $argumentString .= "\${$parameter->getName()}, ";
            if ($parameter->isArray()) {
                $paramString .= "array \${$parameter->getName()}";
            } elseif (!is_null($parameter->getClass())) {
                $paramClass = $parameter->getClass();
                if (!in_array($paramClass->getName(), $this->methodUseClasses)) {
                    $this->methodUseClasses[] = $paramClass->getName();
                }
                $tmpArr = explode("\\", $paramClass->getName());
                $tmpName = array_pop($tmpArr);
                $paramString .= "{$tmpName} \${$parameter->getName()}";
            } else {
                $paramString .= "\${$parameter->getName()}";
            }

            if ($parameter->isDefaultValueAvailable()) {
                $defaultValue = $parameter->getDefaultValue();
                if (is_array($defaultValue)) {
                    $paramString .= " = " . str_replace("\n", "", var_export($defaultValue, true));
                } elseif (is_string($defaultValue)) {
                    $paramString .= " = \"{$defaultValue}\"";
                } elseif (is_null($defaultValue)) {
                    $paramString .= " = null";
                } elseif (is_bool($defaultValue)) {
                    $paramString .= " = " . ($defaultValue ? "true" : "false");
                } else {
                    $paramString .= " = {$defaultValue}";
                }
            }

            $paramString .= ", ";
        }

        $searches = array("__METHOD_DOC__", "__METHOD__", "__PARAMETER__", "__ARGUMENT__");
        $replaces = array(
            $method->getDocComment(),
            $method->getName(),
            trim($paramString, " ,"),
            trim($argumentString, " ,")
        );
        return str_replace($searches, $replaces, $this->template->getMethodTpl());
    }

    /**
     * @param ReflectionMethod $method
     * @return string
     */
    private function buildMethodStringForPHP7(ReflectionMethod $method)
    {
        $argumentString = "";
        $paramString = "";
        foreach ($method->getParameters() as $parameter) {
            $argumentString .= "\${$parameter->getName()}, ";
            $parameterType = $parameter->getType();
            if (!is_null($parameterType)) {
                $typeString = $parameterType->__toString();
                if (!$parameterType->isBuiltin()) {
                    if (!in_array($typeString, $this->methodUseClasses)) {
                        $this->methodUseClasses[] = $typeString;
                    }
                    $tmpArr = explode("\\", $typeString);
                    $typeString = array_pop($tmpArr);
                }
                $paramString .= $typeString . " \${$parameter->getName()}";
            } else {
                $paramString .= "\${$parameter->getName()}";
            }
            if ($parameter->isDefaultValueAvailable()) {
                $defaultValue = $parameter->getDefaultValue();
                if (is_array($defaultValue)) {
                    $paramString .= " = " . str_replace("\n", "", var_export($defaultValue, true));
                } elseif (is_string($defaultValue)) {
                    $paramString .= " = \"{$defaultValue}\"";
                } elseif (is_null($defaultValue)) {
                    $paramString .= " = null";
                } elseif (is_bool($defaultValue)) {
                    $paramString .= " = " . ($defaultValue ? "true" : "false");
                } else {
                    $paramString .= " = {$defaultValue}";
                }
            }
            $paramString .= ", ";
        }
        $returnString = "";
        $methodReturn = $method->getReturnType();
        if (!is_null($methodReturn)) {
            $typeString = $methodReturn->__toString();
            if (!$methodReturn->isBuiltin()) {
                if (!in_array($typeString, $this->methodUseClasses)) {
                    $this->methodUseClasses[] = $typeString;
                }
                $tmpArr = explode("\\", $typeString);
                $typeString = array_pop($tmpArr);
            }
            $returnString = ": {$typeString}";
        }
        $searches = array("__METHOD_DOC__", "__METHOD__", "__PARAMETER__", "__RETURN_TYPE__", "__ARGUMENT__");
        $replaces = array(
            $method->getDocComment(),
            $method->getName(),
            trim($paramString, " ,"),
            $returnString,
            trim($argumentString, " ,")
        );
        return str_replace($searches, $replaces, $this->template->getMethodTpl());
    }

    /**
     * @param string $interface
     * @return array
     * @throws InitiallyRpcException
     */
    private function parseProxyClassInfo($interface)
    {
        $arr = explode("\\", $interface);
        $name = array_pop($arr);
        $matches = array();
        if (!preg_match($this->classNameRule, $name, $matches)) {
            throw new InitiallyRpcException("interface name must ending of 'Interface' like 'DemoServiceInterface'");
        } elseif (empty($arr)) {
            return array(
                "namespace" => Constants::PROXY_PREFIX_NAMESPACE,
                "classname" => $matches[1]
            );
        }

        return array(
            "namespace" => Constants::PROXY_PREFIX_NAMESPACE . "\\" . implode("\\", $arr),
            "classname" => $matches[1]
        );
    }

    /**
     * @param string $namespace
     * @return string
     * @throws InitiallyRpcException
     */
    private function getProxyClassDirAndCreate($namespace)
    {
        $path = trim(str_replace(array(Constants::PROXY_PREFIX_NAMESPACE, "\\"), array("", "/"), $namespace), "/");
        $dir = $this->apiDir . "/" . $path;
        try {
            Util::createDirIfNotExists($dir);
        } catch (InitiallyRpcException $e) {
            throw  new InitiallyRpcException("Proxy api directory create failed");
        }

        return $dir;
    }

}