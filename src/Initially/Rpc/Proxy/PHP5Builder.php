<?php
namespace Initially\Rpc\Proxy;

use ReflectionMethod;
use ReflectionParameter;

class PHP5Builder extends BuilderAbstract
{

    /**
     * Method template searches
     */
    const METHOD_TPL_SEARCHES = array(
        "__METHOD_DOC__",
        "__METHOD__",
        "__PARAMETER__",
        "__ARGUMENT__"
    );

    /**
     * Build method string
     *
     * @param ReflectionMethod $method
     * @return string
     */
    protected function buildMethod(ReflectionMethod $method)
    {
        $argString = "";
        $paramString = "";
        foreach ($method->getParameters() as $value) {
            $argString .= "\${$value->getName()}, ";
            $paramString .= $this->getParamString($value);
        }

        $argString = trim($argString, ", ");
        $paramString = trim($paramString, ", ");
        $replaces = array(
            $method->getDocComment(),
            $method->getName(),
            $paramString,
            $argString
        );
        return str_replace(self::METHOD_TPL_SEARCHES, $replaces, $this->template->getMethodTemplate());
    }

    /**
     * @param ReflectionParameter $parameter
     * @return string
     */
    private function getParamString(ReflectionParameter $parameter)
    {
        $string = "\${$parameter->getName()}";
        if ($parameter->isArray()) {
            $string = "array \${$parameter->getName()}";
        } else if (!is_null($parameter->getClass())) {
            $paramClass = $parameter->getClass();
            $this->useList[] = $paramClass->getName();
            $arr = explode("\\", $paramClass->getName());
            $className = array_pop($arr);
            $string = "{$className} \${$parameter->getName()}";
        }

        if ($parameter->isDefaultValueAvailable()) {
            $defaultValue = $parameter->getDefaultValue();
            if (is_array($defaultValue)) {
                $string .= " = " . str_replace(PHP_EOL, "", var_export($defaultValue, true));
            } else if (is_string($defaultValue)) {
                $string .= " = \"{$defaultValue}\"";
            } else if (is_bool($defaultValue)) {
                $string .= " = " . ($defaultValue ? "true" : "false");
            } else if (is_null($defaultValue)) {
                $string .= " = null";
            } else {
                $string .= " = {$defaultValue}";
            }
        }

        return sprintf("%s, ", $string);
    }

    /**
     * Get template class
     *
     * @return TemplateAbstract
     */
    protected function getTemplate()
    {
        return new PHP5Template();
    }

}