<?php
namespace Initially\Rpc\Transport\Protocol;

use Initially\Rpc\Exception\InitiallyRpcException;

class Http implements Protocol
{

    /**
     * @var resource
     */
    private $curl;

    /**
     * @var int
     */
    private $timeout = 10000;

    /**
     * Http constructor.
     */
    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5000);
        curl_setopt($this->curl, CURLOPT_TIMEOUT_MS, $this->timeout);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
    }

    /**
     * @param string $uri
     * @param string $data
     * @return string
     * @throws InitiallyRpcException
     */
    public function sendData($uri, $data)
    {
        curl_setopt($this->curl, CURLOPT_URL, $uri);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($this->curl);
        $error = curl_error($this->curl);
        $httpCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        if (!empty($error)) {
            throw new InitiallyRpcException("CURL transport error: {$error}");
        } elseif ($httpCode != 200) {
            throw new InitiallyRpcException("Server error, HTTP Code: {$httpCode}. Server sent: {$result}");
        }
        return $result;
    }

    /**
     * Close curl
     */
    public function __destruct()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
        }
    }

}