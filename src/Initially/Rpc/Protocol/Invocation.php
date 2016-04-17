<?php
namespace Initially\Rpc\Protocol;

class Invocation
{

    /**
     * @var string
     */
    protected $methodName;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * Invocation constructor.
     * @param string $methodName
     * @param array $arguments
     */
    public function __construct($methodName, array $arguments)
    {
        $this->methodName = $methodName;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param string $methodName
     */
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

}