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
     * @var Exception
     */
    private $exception;

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
     * @return Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param Exception $exception
     */
    public function setException($exception)
    {
        $this->exception = $exception;
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