<?php
namespace Initially\Rpc\Core\Engine;

use Initially\Rpc\Core\Engine\Config\Client as ClientConfig;
use Initially\Rpc\Core\Engine\Config\Factory as ConfigFactory;
use Initially\Rpc\Core\Engine\Config\Service;
use Initially\Rpc\Proxy\BuilderAbstract;
use Initially\Rpc\Proxy\PHP5Builder;
use Initially\Rpc\Proxy\PHP7Builder;

class Client
{

    use AppTrait;

    /**
     * @var ClientConfig
     */
    private $config;

    /**
     * @var BuilderAbstract
     */
    private $builder;

    /**
     * @var bool
     */
    private $isPHP7 = false;

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
        $this->isPHP7 = version_compare(PHP_VERSION, "7.0.0", ">=");
        $this->builder = $this->isPHP7 ? new PHP7Builder() : new PHP5Builder();
    }

    public function buildProxyOrNot()
    {
        $services = $this->config->getServices();
        foreach ($services as $service) {
            $this->builder->create($service->getInterface());
        }
    }

    /**
     * @return ClientConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

}