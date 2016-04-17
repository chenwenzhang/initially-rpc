<?php
namespace Initially\Rpc\Core\Config;

use Initially\Rpc\Core\Config\Client\Client;
use Initially\Rpc\Core\Config\Server\Server;
use Initially\Rpc\Exception\InitiallyRpcException;

class Factory
{

    /**
     * @var array
     */
    private static $serverMapping = array();

    /**
     * @var array
     */
    private static $clientMapping = array();

    /**
     * @param $key
     * @return Server
     * @throws InitiallyRpcException
     */
    public static function getServer($key)
    {
        if (!isset(self::$serverMapping[$key])) {
            throw new InitiallyRpcException("");
        }
        return self::$serverMapping[$key];
    }

    /**
     * @param string $key
     * @param Server $config
     */
    public static function setServer($key, Server $config)
    {
        self::$serverMapping[$key] = $config;
    }

    /**
     * @param string $key
     * @return Client
     * @throws InitiallyRpcException
     */
    public static function getClient($key)
    {
        if (!isset(self::$clientMapping[$key])) {
            throw new InitiallyRpcException("");
        }
        return self::$clientMapping[$key];
    }

    /**
     * @param string $key
     * @param Client $config
     */
    public static function setClient($key, Client $config)
    {
        self::$clientMapping[$key] = $config;
    }

}