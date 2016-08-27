<?php
namespace Initially\Rpc\Transport\Protocol;

use Initially\Rpc\Exception\InitiallyRpcException;

class Factory
{

    private static $protocols;

    /**
     * Get protocol
     *
     * @param string $type
     * @return Protocol
     * @throws InitiallyRpcException
     */
    public static function getProtocol($type)
    {
        if (isset(self::$protocols[$type])) {
            return self::$protocols[$type];
        }

        switch ($type) {
            case Protocol::HTTP:
                self::$protocols[$type] = new Http();
                break;
            default:
                throw new InitiallyRpcException("undefined transport protocol");
        }

        return self::$protocols[$type];
    }

}