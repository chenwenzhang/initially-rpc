<?php
namespace Initially\Rpc\Core\Engine;

use Initially\Rpc\Core\Config\Factory as ConfigFactory;
use Initially\Rpc\Core\Config\Loader as ConfigLoader;
use Initially\Rpc\Exception\InitiallyRpcException;
use Initially\Rpc\Protocol\Invocation;
use Initially\Rpc\Protocol\Protocol;
use Initially\Rpc\Transport\Response;
use Initially\Rpc\Transport\Transport;

class ServerApplication implements Application
{

    /**
     * @var ServerApplication
     */
    private static $instance;

    /**
     * @var string
     */
    private $configFile;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * @var Protocol
     */
    private $protocol;

    /**
     * ServerApplication constructor.
     * @param string $configFile
     * @throws InitiallyRpcException
     */
    public function __construct($configFile)
    {
        $this->configFile = realpath($configFile);
        if ($this->configFile === false) {
            throw new InitiallyRpcException("Server config file error");
        }

        $this->transport = new Transport();
        $this->protocol = new Protocol();
    }

    /**
     * @throws InitiallyRpcException
     */
    public function run()
    {
        // exception handler
        set_exception_handler(array($this, "exceptionHandler"));
        // set as global
        $this->setAsGlobal();
        // load server config
        ConfigLoader::loadServer($this->configFile);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $request = $this->transport->receive();
            $interface = $request->getInterface();
            $target = $this->getTarget($interface);
            $invoker = $this->protocol->export($target);
            $invocation = new Invocation($request->getMethodName(), $request->getArguments());
            $invoker->invoke($invocation);
        } else {
            echo "GET";
        }
    }

    /**
     * @return Transport
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @return Protocol
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set server application as global
     */
    private function setAsGlobal()
    {
        self::$instance = $this;
    }

    /**
     * @param string $interface
     * @throws InitiallyRpcException
     */
    private function getTarget($interface)
    {
        $config = ConfigFactory::getServer($interface);
        $reference = $config->getReference();
        if (!class_exists($reference)) {
            throw new InitiallyRpcException("reference class not exists");
        }
        return new $reference();
    }

    /**
     * @return ServerApplication
     * @throws InitiallyRpcException
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            throw new InitiallyRpcException("server application is not global");
        }
        return self::$instance;
    }

    /**
     * @param \Throwable $e
     */
    public function exceptionHandler($e) {
        $response = new Response();
        $response->setException($e);
        $response->setHasException(true);
        $this->transport->reply($response);
    }

}