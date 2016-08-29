<?php
namespace Initially\Rpc\Core\Engine;

use Initially\Rpc\Exception\InitiallyRpcException;

trait AppTrait
{

    /**
     * @var string
     */
    protected $configFile;

    /**
     * @var $this
     */
    protected static $instance;

    /**
     * @return $this
     * @throws InitiallyRpcException
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            throw new InitiallyRpcException("Not global");
        }

        return self::$instance;
    }

    /**
     * Set as global
     */
    protected function setAsGlobal()
    {
        self::$instance = $this;
    }

    /**
     * @param string $configFile
     * @throws InitiallyRpcException
     */
    public function setConfigFileAndCheck($configFile)
    {
        if (!is_file($configFile)) {
            throw new InitiallyRpcException("Client config file not exists");
        }

        $this->configFile = $configFile;
    }

    /**
     * @return string
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }

}