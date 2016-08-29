<?php
namespace Initially\Rpc\Core\Engine\Config;

class Factory
{

    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var Server
     */
    protected static $server;

    /**
     * @return Client
     * @throws \Initially\Rpc\Exception\InitiallyRpcException
     */
    public static function getClient()
    {
        if (!isset(self::$client)) {
            self::$client = Loader::client();
        }

        return self::$client;
    }

    /**
     * @return Server
     * @throws \Initially\Rpc\Exception\InitiallyRpcException
     */
    public static function getServer()
    {
        if (!isset(self::$server)) {
            self::$server = Loader::server();
        }

        return self::$server;
    }

}