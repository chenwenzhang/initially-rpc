<?php
namespace Initially\Rpc\Transport;

class Request
{

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $arguments;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->version = "";
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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