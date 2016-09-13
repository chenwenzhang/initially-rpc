<?php
namespace Initially\Rpc\Core\Engine;

use Composer\Script\Event;
use Initially\Rpc\Core\Config\Factory as ConfigFactory;
use Initially\Rpc\Core\Config\Loader as ConfigLoader;
use Initially\Rpc\Core\Engine\Config\Service;
use Initially\Rpc\Core\Support\Util;
use Initially\Rpc\Proxy\Builder as ProxyBuilder;

class ComposerScriptHandler
{

    /**
     * Rebuild client service proxy
     */
    public static function rebuildClientServiceProxy(Event $event)
    {
        $io = $event->getIO();
        $extra = $event->getComposer()->getPackage()->getExtra();
        if (isset($extra["initially-rpc-config-file"])) {
            $rootDir = getcwd();
            $configFile = $rootDir . "/" . $extra["initially-rpc-config-file"];
            if (is_file($configFile)) {
                $client = new Client($configFile);
                $builder = $client->getBuilder();
                $config = $client->getConfig();
                $services = $config->getServices();
                $io->write("Initially Rpc: build proxy start");
                foreach ($services as $service) {
                    $io->write("Initially Rpc: build {$service->getInterface()}");
                    $builder->create($service->getInterface());
                }
                $io->write("Initially Rpc: build proxy end");
            } else {
                $io->write("Initially Rpc: client config file error [\"{$configFile}\"]");
            }
        } else {
            $io->write("Initially Rpc: not configured composer extra [\"initially-rpc-config-file\"]");
        }
    }

}