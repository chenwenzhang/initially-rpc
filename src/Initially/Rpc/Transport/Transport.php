<?php
namespace Initially\Rpc\Transport;

use Initially\Rpc\Core\Config\Factory as ConfigFactory;
use Initially\Rpc\Exception\InitiallyRpcException;
use Initially\Rpc\Transport\Protocol\Factory as TransportProtocolFactory;
use Initially\Rpc\Transport\Protocol\Protocol as TransportProtocol;

class Transport
{

    /**
     * Http protocol
     */
    const PROTOCOL_HTTP = "http";

    /**
     * @var array
     */
    protected static $transports;

    /**
     * @var TransportProtocol
     */
    protected $protocol;

    /**
     * Transport constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->protocol = TransportProtocolFactory::getProtocol($type);
    }

    /**
     * Transport factory
     *
     * @param string $type
     * @return Transport
     */
    public static function factory($type)
    {
        if (isset(self::$transports[$type])) {
            return self::$transports[$type];
        }

        self::$transports[$type] = new Transport($type);
        return self::$transports[$type];
    }

    /**
     * Send Rpc Request
     *
     * @param Request $request
     * @return mixed
     * @throws InitiallyRpcException
     */
    public function send(Request $request)
    {
        $interface = $request->getInterface();
        $config = ConfigFactory::getClient($interface);
        $url = $config->getUrl();

        $requestRaw = Formatter::serialize($request);
        $responseRaw = $this->protocol->sendData($url, $requestRaw);
        $response = Formatter::unserialize($responseRaw);
        if (!($response instanceof Response)) {
            throw new InitiallyRpcException("illegal response");
        } elseif ($response->isHasException()) {
            $e = $response->getException();
            throw new InitiallyRpcException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        return $response->getResult();
    }

    /**
     * Receive Rpc Request
     *
     * @return Request
     * @throws InitiallyRpcException
     */
    public function receive()
    {
        $request = $this->receive();
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
        $this->reply($response);
    }

}