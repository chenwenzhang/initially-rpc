<?php
namespace Initially\Rpc\Transport;

use Initially\Rpc\Exception\InitiallyRpcException;

class Transport
{

    /**
     * @param Request $request
     */
    public function send(Request $request)
    {
        $requestRaw = Formatter::serialize($request);
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
        echo Formatter::serialize($response);
    }

}