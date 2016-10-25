<?php
namespace Initially\Rpc\Transport;

use Exception;
use Initially\Rpc\Core\Support\Version;

class Response
{

    /**
     * @var string
     */
    private $version;

    /**
     * @var mixed
     */
    private $result;

    /**
     * class name of exception
     * @var string
     */
    private $exception;

    /**
     * exception message
     * @var string
     */
    private $exceptionMessage;

    /**
     * @var bool
     */
    private $hasException;

    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->version = Version::get();
        $this->hasException = false;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param string $exception
     */
    public function setException($exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return string
     */
    public function getExceptionMessage()
    {
        return $this->exceptionMessage;
    }

    /**
     * @param string $exceptionMessage
     */
    public function setExceptionMessage($exceptionMessage)
    {
        $this->exceptionMessage = $exceptionMessage;
    }

    /**
     * @return bool
     */
    public function isHasException()
    {
        return $this->hasException;
    }

    /**
     * @param bool $hasException
     */
    public function setHasException($hasException)
    {
        $this->hasException = (bool) $hasException;
    }

}