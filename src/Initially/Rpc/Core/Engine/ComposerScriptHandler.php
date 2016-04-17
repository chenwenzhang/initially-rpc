<?php
namespace Initially\Rpc\Core\Engine;

use Composer\Script\Event;
use Initially\Rpc\Core\Config\Factory as ConfigFactory;
use Initially\Rpc\Core\Config\Loader as ConfigLoader;
use Initially\Rpc\Core\Support\Util;
use Initially\Rpc\Proxy\Builder as ProxyBuilder;

class ComposerScriptHandler
{

    /**
     * Rebuild client service proxy
     */
    public static function rebuildClientServiceProxy(Event $event)
    {
        if (null !== ($fileInfo = Util::getClientConfigFileInfo())) {
            ConfigLoader::loadClient($fileInfo["file"]);
            $clientConfigs = ConfigFactory::getClientAll();
            $proxyBuilder = new ProxyBuilder();
            foreach ($clientConfigs as $config) {
                $proxyBuilder->createProxy($config->getInterface());
            }
        }
    }

}