<?php
namespace Initially\Rpc\Transport;

use Initially\Rpc\Core\Config\Factory;
use Initially\Rpc\Exception\InitiallyRpcException;
use Initially\Rpc\Transport\Protocol\Http;
use Throwable;

class Transport
{

    /**
     * @param Request $request
     * @throws InitiallyRpcException
     * @throws Throwable
     */
    public function send(Request $request)
    {
        $interface = $request->getInterface();
        $config = Factory::getClient($interface);
        $url = $config->getUrl();
        $requestRaw = Formatter::serialize($request);
        $responseRaw = $this->postData($url, $requestRaw);
        $response = Formatter::unserialize($responseRaw);
        if (!($response instanceof Response)) {
            throw new InitiallyRpcException("illegal response");
        } elseif ($response->isHasException()) {
            $exception = $response->getException();
            throw new InitiallyRpcException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious()
            );
        }
        return $response->getResult();
    }

    /**
     * @param string $uri
     * @param string $data
     * @return string
     * @throws InitiallyRpcException
     */
    private function postData($uri, $data)
    {
        static $protocol;
        if (!isset($protocol)) {
            $protocol = new Http();
        }

        return $protocol->sendData($uri, $data);
    }

    /**
     * @return Request
     * @throws InitiallyRpcException
     */
    public function receive()
    {
        $requestRaw = file_get_contents("php://input");
        $request = Formatter::unserialize($requestRaw);
        if (!($request instanceof Request)) {
            throw new InitiallyRpcException("illegal request");
        }
        return $request;
    }

    /**
     * Reply client request
     *
     * @param Response $response
     */
    public function reply(Response $response)
    {
        header("Content-Type: text/plain;charset=utf-8");
        echo Formatter::serialize($response);
    }

}