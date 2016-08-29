<?php
namespace Initially\Rpc\Protocol;

use Initially\Rpc\Core\Engine\Config\Client as ClientConfig;
use Initially\Rpc\Core\Engine\Config\Factory as ConfigFactory;
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
     * @var ClientConfig
     */
    private $config;

    /**
     * ClientInvoker constructor.
     *
     * @param $interface
     */
    public function __construct($interface)
    {
        $this->interface = $interface;
        $this->config = ConfigFactory::getClient();
    }

    /**
     * Invoke service
     *
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
        $transport = Transport::factory($this->config->getTransport());
        if (!($transport instanceof Transport)) {
            throw new InitiallyRpcException("missing transport");
        }

        return $transport->send($request);
    }

}