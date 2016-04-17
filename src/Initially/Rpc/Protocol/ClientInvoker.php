<?php
namespace Initially\Rpc\Protocol;

use Initially\Rpc\Core\Support\Registry;
use Initially\Rpc\Exception\InitiallyRpcException;
use Initially\Rpc\Transport\Request;
use Initially\Rpc\Transport\Transport;

class ClientInvoker implements Invoker
{

    /**
     * @var string
     */
    private $interface;

    /**
     * ClientInvoker constructor.
     * @param string $type
     */
    public function __construct($interface)
    {
        $this->interface = $interface;
    }

    /**
     * @param Invocation $invocation
     * @return mixed
     * @throws InitiallyRpcException
     */
    public function invoke(Invocation $invocation)
    {
        $request = new Request();
        $request->setInterface($this->interface);
        $request->setMethodName($invocation->getMethodName());
        $request->setArguments($invocation->getArguments());
        $transport = Registry::get("transport");
        if (!($transport instanceof Transport)) {
            throw new InitiallyRpcException("missing transport");
        }
        return $transport->send($request);
    }

}