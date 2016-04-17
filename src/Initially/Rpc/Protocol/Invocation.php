<?php
namespace Initially\Rpc\Protocol;

class Invocation
{

    protected $methodName;

    protected $arguments;

    public function __construct($methodName, array $arguments)
    {
        $this->methodName = $methodName;
        $this->arguments = $arguments;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

}