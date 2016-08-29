<?php
namespace Initially\Rpc\Core\Engine;

use Initially\Rpc\Core\Engine\Config\Client as ClientConfig;
use Initially\Rpc\Core\Engine\Config\Factory as ConfigFactory;

class Client
{

    use AppTrait;

    /**
     * @var ClientConfig
     */
    private $config;

    /**
     * Client constructor.
     *
     * @param string $configFile
     */
    public function __construct($configFile)
    {
        $this->setAsGlobal();
        $this->setConfigFileAndCheck($configFile);
        $this->config = ConfigFactory::getClient();
    }

    public function run()
    {

    }

    /**
     * @return ClientConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

}