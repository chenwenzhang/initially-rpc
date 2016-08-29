<?php
namespace Initially\Rpc\Transport\Protocol;

use Initially\Rpc\Core\Engine\Server as ServerApp;
use Initially\Rpc\Exception\InitiallyRpcException;
use Initially\Rpc\Protocol\Invocation;
use Initially\Rpc\Transport\Formatter;
use Initially\Rpc\Transport\Request;
use Initially\Rpc\Transport\Response;

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
     * @return Request
     */
    public function receive()
    {
        $requestRaw = file_get_contents("php://input");
        $request = Formatter::unserialize($requestRaw);
        return $request;
    }

    /**
     * @param Response $response
     * @return mixed
     */
    public function reply(Response $response)
    {
        header("Content-Type: text/plain;charset=utf-8");
        echo Formatter::serialize($response);
    }

    /**
     * Handle
     */
    public function handle()
    {
        $requestMethod = strtoupper($_SERVER["REQUEST_METHOD"]);
        if ($requestMethod === "POST") {
            $request = $this->receive();
            $target = ServerApp::getInstance()->getTarget($request->getInterface());
            $invoker = ServerApp::getInstance()->getProtocol()->export($target);
            $invocation = new Invocation($request->getMethodName(), $request->getArguments());
            $response = $invoker->invoke($invocation);
            $this->reply($response);
        } else {
            echo "GET";
        }
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