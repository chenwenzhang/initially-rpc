<?php
namespace Initially\Rpc\Core\Engine;

use Exception;
use Initially\Rpc\Core\Engine\Config\Factory as ConfigFactory;
use Initially\Rpc\Core\Engine\Config\Server as ServerConfig;
use Initially\Rpc\Exception\InitiallyRpcException;
use Initially\Rpc\Protocol\Protocol;
use Initially\Rpc\Transport\Response;
use Initially\Rpc\Transport\Transport;

class Server
{

    use AppTrait;

    /**
     * @var ServerConfig
     */
    protected $config;

    /**
     * @var Transport
     */
    protected $transport;

    /**
     * @var Protocol
     */
    protected $protocol;

    /**
     * Server constructor.
     *
     * @param string $configFile
     */
    public function __construct($configFile)
    {
        $this->setAsGlobal();
        $this->setConfigFileAndCheck($configFile);
        $this->config = ConfigFactory::getServer();
        $this->transport = Transport::factory($this->config->getTransport());
        $this->protocol = new Protocol();
        set_exception_handler(array($this, "exceptionHandler"));
    }

    /**
     * Handle
     */
    public function handle()
    {
        $this->transport->handle();
    }

    /**
     * Exception handler
     *
     * @param Exception $e
     */
    public function exceptionHandler($e)
    {
        $response = new Response();
        $response->setException(get_class($e));
        $response->setExceptionMessage($e->getMessage());
        $response->setHasException(true);
        $this->transport->reply($response);
    }

    /**
     * @return ServerConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Transport
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param Transport $transport
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return Protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param Protocol $protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * Get target
     *
     * @param $interface
     * @return mixed
     * @throws InitiallyRpcException
     */
    public function getTarget($interface)
    {
        $reference = $this->config->getService($interface)->getReference();
        if (!class_exists($reference)) {
            throw new InitiallyRpcException("Reference class not exists");
        }

        return new $reference();
    }

}