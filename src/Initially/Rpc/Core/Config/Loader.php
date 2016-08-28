<?php
namespace Initially\Rpc\Core\Config;

use Initially\Rpc\Core\Config\Client;
use Initially\Rpc\Core\Config\Server;
use Initially\Rpc\Exception\InitiallyRpcException;

class Loader
{

    /**
     * Load client config
     *   <format>
     *     {
     *       "global": {
     *         "url": "",
     *         "transport": ""
     *       },
     *       "services": [
     *         {
     *           "interface": "",
     *           "url": "",
     *           "transport": ""
     *         },
     *         ...
     *       ]
     *     }
     *   </format>
     *
     * @param string $configFile
     * @throws InitiallyRpcException
     */
    public static function loadClient($configFile)
    {
        if (!is_file($configFile)) {
            throw new InitiallyRpcException("Client config file is missing");
        }

        $clientConfig = json_decode(file_get_contents($configFile), true);
        if (!is_array($clientConfig)) {
            throw new InitiallyRpcException("Client config error");
        }

        $globalConfig = isset($clientConfig["global"]) ? $clientConfig["global"] : array();
        $isSetGlobalUrl = isset($globalConfig["url"]);
        $isSetGlobalTransport = isset($globalConfig["transport"]);
        if (isset($clientConfig["services"]) && !empty($clientConfig["services"])) {
            foreach ($clientConfig["services"] as $serviceConfig) {
                if (!is_array($serviceConfig)) {
                    throw new InitiallyRpcException("Client config error: format error");
                } else if (!isset($serviceConfig["interface"])) {
                    throw new InitiallyRpcException("Client config error: undefined interface");
                } else if (!isset($serviceConfig["url"]) && !$isSetGlobalUrl) {
                    throw new InitiallyRpcException("Client config error: undefined url ({$serviceConfig['interface']})");
                } else if (!isset($serviceConfig["transport"]) && !$isSetGlobalTransport) {
                    throw new InitiallyRpcException("Client config error: undefined transport ({$serviceConfig['interface']})");
                }

                $url = isset($serviceConfig["url"]) ?
                    $serviceConfig["url"] : $globalConfig["url"];
                $transport = isset($serviceConfig["transport"]) ?
                    $serviceConfig["transport"] : $globalConfig["transport"];
                Factory::setClient(
                    $serviceConfig["interface"],
                    new Client($url, $serviceConfig["interface"], $transport)
                );
            }
        }
    }

    /**
     * Load server config
     *   <format>
     *     {
     *       "services": [
     *         {
     *           "interface": "",
     *           "reference": ""
     *         },
     *         ...
     *       ]
     *     }
     *   </format>
     *
     * @param string $configFile
     * @throws InitiallyRpcException
     */
    public static function loadServer($configFile)
    {
        if (!is_file($configFile)) {
            throw new InitiallyRpcException("Server config file is missing");
        }

        $serverConfig = json_decode(file_get_contents($configFile), true);
        if (!is_array($serverConfig)) {
            throw new InitiallyRpcException("Server config error");
        }

        if (!isset($serverConfig["transport"])) {
            throw new InitiallyRpcException("Server transport undefined");
        } else {
            Factory::setServerTransportProtocol($serverConfig["transport"]);
        }

        if (isset($serverConfig["services"]) && !empty($serverConfig["services"])) {
            foreach ($serverConfig["services"] as $serviceConfig) {
                if (
                    !is_array($serviceConfig) ||
                    !isset($serviceConfig["interface"]) ||
                    !isset($serviceConfig["reference"])
                ) {
                    throw new InitiallyRpcException("Server service config error");
                }
                $config = new Server($serviceConfig["interface"], $serviceConfig["reference"]);
                Factory::setServer($serviceConfig["interface"], $config);
            }
        }
    }

}