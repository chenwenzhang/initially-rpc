<?php
namespace Initially\Rpc\Core\Engine;

use Initially\Rpc\Core\Engine\Config\Client as ClientConfig;
use Initially\Rpc\Core\Engine\Config\Factory as ConfigFactory;
use Initially\Rpc\Core\Support\Util;
use Initially\Rpc\Exception\InitiallyRpcException;
use Initially\Rpc\Proxy\BuilderAbstract;
use Initially\Rpc\Proxy\PHP5Builder;
use Initially\Rpc\Proxy\PHP7Builder;

// framework root path
define("INITIALLY_RPC_ROOT_PATH", dirname(dirname(dirname(dirname(dirname(__DIR__))))));

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

    /**
     * Build proxy or not
     *
     * @throws \Initially\Rpc\Exception\InitiallyRpcException
     */
    public function buildProxyOrNot()
    {
        if (!Util::existsDirWritable(INITIALLY_RPC_ROOT_PATH)) {
            throw new InitiallyRpcException("rpc framework root dir not to be write");
        }

        if ($this->compareConfigFile()) {
            $services = $this->config->getServices();
            foreach ($services as $service) {
                $this->builder->create($service->getInterface());
            }
        }
    }

    /**
     * @return ClientConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return BuilderAbstract
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * 比较配置文件的MD5,如果没有改变返回false,否则返回true
     *
     * @return bool
     * @throws InitiallyRpcException
     */
    private function compareConfigFile()
    {
        $cachePath = INITIALLY_RPC_ROOT_PATH . "/src";
        $cacheFile = $cachePath . "/info.cache";
        Util::createDirIfNotExists($cachePath);
        $configSha1 = sha1_file($this->configFile);
        if (!is_file($cacheFile)) {
            if (!@file_put_contents($cacheFile, $configSha1)) {
                throw new InitiallyRpcException("write config file md5 failed");
            }

            $oldConfigSha1 = $configSha1;
        } else {
            $oldConfigSha1 = file_get_contents($cacheFile);
            if ($configSha1 != $oldConfigSha1 && !@file_put_contents($cacheFile, $configSha1)) {
                throw new InitiallyRpcException("write config file md5 failed");
            }
        }

        return $configSha1 == $oldConfigSha1;
    }

}