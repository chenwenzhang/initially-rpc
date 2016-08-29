<?php
namespace Initially\Rpc\Proxy;

use ReflectionMethod;
use ReflectionParameter;

class PHP7Builder extends BuilderAbstract
{

    /**
     * Method template searches
     */
    const METHOD_TPL_SEARCHES = array(
        "__METHOD_DOC__",
        "__METHOD__",
        "__PARAMETER__",
        "__RETURN_TYPE__",
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
        $returnString = "";
        foreach ($method->getParameters() as $value) {
            $argString .= "\${$value->getName()}, ";
            $paramString .= $this->getParamString($value);
        }

        $methodReturn = $method->getReturnType();
        if (!is_null($methodReturn)) {
            if (!$methodReturn->isBuiltin()) {
                $this->useList[] = sprintf("%s", $methodReturn);
                $arr = explode("\\", $methodReturn);
                $methodReturn = array_pop($arr);
            }

            $returnString = ": {$methodReturn}";
        }

        $argString = trim($argString, ", ");
        $paramString = trim($paramString, ", ");
        $replaces = array(
            $method->getDocComment(),
            $method->getName(),
            $paramString,
            $returnString,
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
        $type = $parameter->getType();
        if (!is_null($type)) {
            if (!$type->isBuiltin()) {
                $this->useList[] = sprintf("%s", $type);
                $arr = explode("\\", $type);
                $type = array_pop($arr);
            }

            $string  = "{$type} {$string}";
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
        return new PHP7Template();
    }

}