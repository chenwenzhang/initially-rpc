<?php
namespace Initially\Rpc\Transport;

use Initially\Rpc\Core\Support\Version;
use Throwable;

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
     * @var Throwable
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
     * @return Throwable
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param Throwable $exception
     */
    public function setException(Throwable $exception)
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