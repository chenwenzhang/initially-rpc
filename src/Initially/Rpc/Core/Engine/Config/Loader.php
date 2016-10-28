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
     *       "proxyRootDir": "", // 代理类根目录，相对于配置文件存放的目录，目录必须存在
     *       "replace": { // 接口命名空间中需要替换的字段
     *         "search1": "replace1",
     *         "search2": "replace2"
     *       },
     *       "services": [
     *         {
     *           "interface": "",
     *           "url": "",
     *           "transport": "",
     *           "replace-key": ""
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
        } else if (!isset($config["proxyRootDir"])) {
            throw new InitiallyRpcException("Client config error: proxy root dir undefined");
        }

        $configDir = dirname($configFile);
        $realProxyRootDir = realpath($configDir . "/" . $config["proxyRootDir"]);
        if ($realProxyRootDir === false) {
            throw new InitiallyRpcException("Client config error: proxy dir not exists");
        }

        $clientConfig = new Client();
        $issetGlobalUrl = isset($config["url"]);
        $issetGlobalTransport = isset($config["transport"]);
        $issetGlobalUrl && $clientConfig->setUrl($config["url"]);
        $issetGlobalTransport && $clientConfig->setTransport($config["transport"]);
        $clientConfig->setProxyRootDir($realProxyRootDir);
        if (isset($config["replace"]) && is_array($config["replace"])) {
            $clientConfig->setReplace($config["replace"]);
        }

        if (isset($config["services"]) && is_array($config["services"]) && !empty($config["services"])) {
            foreach ($config["services"] as $key => $value) {
                $issetUrl = isset($value["url"]);
                $issetTransport = isset($value["transport"]);
                $issetReplaceKey = isset($value["replace-key"]);
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
                $service->setReplaceKey($issetReplaceKey ? $value["replace-key"] : "");
                $clientConfig->addService($service);
            }
        }

        return $clientConfig;
    }

}