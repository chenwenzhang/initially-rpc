<?php
namespace Initially\Rpc\Core\Engine\Config;

use Initially\Rpc\Core\Engine\Client as ClientApp;
use Initially\Rpc\Core\Engine\Server as ServerApp;
use Initially\Rpc\Exception\InitiallyRpcException;

class Loader
{

    /**
     * Load server config
     *   <format>
     *     {
     *       "transport": "",
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
     * @return Server
     * @throws InitiallyRpcException
     */
    public static function server()
    {
        $configFile = ServerApp::getInstance()->getConfigFile();
        $content = file_get_contents($configFile);
        $config = json_decode($content, true);
        if (!is_array($config)) {
            throw new InitiallyRpcException("Server config error: format error");
        } else if (!isset($config["transport"])) {
            throw new InitiallyRpcException("Server config error: undefined transport");
        }

        $serverConfig = new Server();
        $serverConfig->setTransport($config["transport"]);
        if (isset($config["services"]) && is_array($config["services"]) && !empty($config["services"])) {
            foreach ($config["services"] as $key => $value) {
                if (!is_array($value)) {
                    throw new InitiallyRpcException("Server config error: service format error");
                } else if (!isset($value["interface"])) {
                    throw new InitiallyRpcException("Server config error: undefined interface");
                } else if (!isset($value["reference"])) {
                    throw new InitiallyRpcException("Server config error: undefined reference");
                }

                $service = new Service();
                $service->setInterface($value["interface"]);
                $service->setReference($value["reference"]);
                $serverConfig->addService($service);
            }
        }

        return $serverConfig;
    }

    /**
     * Load client config
     *   <format>
     *     {
     *       "url": "",
     *       "transport": "",
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
     * @return Client
     * @throws InitiallyRpcException
     */
    public static function client()
    {
        $configFile = ClientApp::getInstance()->getConfigFile();
        $content = file_get_contents($configFile);
        $config = json_decode($content, true);
        if (!is_array($config)) {
            throw new InitiallyRpcException("Client config error: format error");
        }

        $clientConfig = new Client();
        $issetGlobalUrl = isset($config["url"]);
        $issetGlobalTransport = isset($config["transport"]);
        $issetGlobalUrl && $clientConfig->setUrl($config["url"]);
        $issetGlobalTransport && $clientConfig->setTransport($config["transport"]);
        if (isset($config["services"]) && is_array($config["services"]) && !empty($config["services"])) {
            foreach ($config["services"] as $key => $value) {
                $issetUrl = isset($value["url"]);
                $issetTransport = isset($value["transport"]);
                if (!is_array($value)) {
                    throw new InitiallyRpcException("Client config error: undefined service");
                } else if (!isset($value["interface"])) {
                    throw new InitiallyRpcException("Client config error: undefined interface");
                } else if (!$issetGlobalUrl && $issetUrl) {
                    throw new InitiallyRpcException("Client config error: undefined url");
                } else if (!$issetGlobalTransport && $issetTransport) {
                    throw new InitiallyRpcException("Client config error: undefined transport");
                }

                $service = new Service();
                $service->setInterface($value["interface"]);
                $service->setUrl($issetUrl ? $value["url"] : $clientConfig->getUrl());
                $service->setTransport($issetTransport ? $value["transport"] : $clientConfig->getTransport());
                $clientConfig->addService($service);
            }
        }

        return $clientConfig;
    }

}