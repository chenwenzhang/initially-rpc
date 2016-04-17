<?php
namespace Initially\Rpc\Core\Config;

use Initially\Rpc\Exception\InitiallyRpcException;

class Loader
{

    /**
     * Load client config
     *
     * @param string $configFile
     * @throws InitiallyRpcException
     */
    public static function loadClient($configFile)
    {
        if (!is_file($configFile)) {
            throw new InitiallyRpcException();
        }
        $config = file_get_contents($configFile);
    }

    /**
     * Load server config
     *
     * @param string $configFile
     * @throws InitiallyRpcException
     */
    public static function loadServer($configFile)
    {
        if (!is_file($configFile)) {
            throw new InitiallyRpcException();
        }
    }

}