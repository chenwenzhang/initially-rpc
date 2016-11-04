<?php
namespace Initially\Rpc\Transport;

use Exception;
use Initially\Rpc\Core\Engine\Config\Factory as ConfigFactory;
use Initially\Rpc\Core\Support\LoggerProxy;
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
        $service = ConfigFactory::getClient()->getService($interface);
        $url = $service->getUrl();

        $requestRaw = Formatter::serialize($request);
        $responseRaw = $this->protocol->sendData($url, $requestRaw);
        $response = @Formatter::unserialize($responseRaw);
        if (!($response instanceof Response)) {
            LoggerProxy::getInstance()->error("illegal response", array($responseRaw));
            throw new InitiallyRpcException("illegal response");
        } elseif ($response->isHasException()) {
            $exception = $response->getException();
            if (is_object($exception) && $exception instanceof Exception) {
                throw $exception;
            } else if (is_string($exception)) {
                if (class_exists($exception)) {
                    throw new $exception($response->getExceptionMessage());
                } else {
                    throw new InitiallyRpcException($response->getExceptionMessage());
                }
            }
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
        $request = $this->protocol->receive();
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
        $this->protocol->reply($response);
    }

    /**
     * Handle
     */
    public function handle()
    {
        $this->protocol->handle();
    }

}