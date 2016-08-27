<?php
namespace Initially\Rpc\Core\Config;

use Initially\Rpc\Core\Config\Client;
use Initially\Rpc\Core\Config\Server;
use Initially\Rpc\Exception\InitiallyRpcException;

class Factory
{

    /**
     * Server config mapping
     *
     * @var array
     */
    private static $serverMapping = array();

    /**
     * @var string
     */
    private static $serverTransportProtocol;

    /**
     * Client config mapping
     *
     * @var array
     */
    private static $clientMapping = array();

    /**
     * Get server config
     *
     * @param $key
     * @return Server
     * @throws InitiallyRpcException
     */
    public static function getServer($key)
    {
        if (!isset(self::$serverMapping[$key])) {
            throw new InitiallyRpcException("server interface undefined");
        }

        return self::$serverMapping[$key];
    }

    /**
     * Get all server config
     *
     * @return array
     */
    public static function getServerAll()
    {
        return self::$serverMapping;
    }

    /**
     * Set server config
     *
     * @param string $key
     * @param Server $config
     */
    public static function setServer($key, Server $config)
    {
        self::$serverMapping[$key] = $config;
    }

    /**
     * @return string
     */
    public static function getServerTransportProtocol()
    {
        return self::$serverTransportProtocol;
    }

    /**
     * @param string $serverTransportProtocol
     */
    public static function setServerTransportProtocol($serverTransportProtocol)
    {
        self::$serverTransportProtocol = $serverTransportProtocol;
    }

    /**
     * Get client config
     *
     * @param string $key
     * @return Client
     * @throws InitiallyRpcException
     */
    public static function getClient($key)
    {
        if (!isset(self::$clientMapping[$key])) {
            throw new InitiallyRpcException("client interface undefined");
        }

        return self::$clientMapping[$key];
    }

    /**
     * Get all client config
     *
     * @return array
     */
    public static function getClientAll()
    {
        return self::$clientMapping;
    }

    /**
     * Set client config
     *
     * @param string $key
     * @param Client $config
     */
    public static function setClient($key, Client $config)
    {
        self::$clientMapping[$key] = $config;
    }

}