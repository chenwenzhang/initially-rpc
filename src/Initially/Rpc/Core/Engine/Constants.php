<?php
namespace Initially\Rpc\Core\Engine;

define("INITIALLY_RPC_ROOT", dirname(dirname(dirname(dirname(__DIR__)))));

class Constants
{

    const ROOT_PATH = INITIALLY_RPC_ROOT;

    const VAR_PATH = self::ROOT_PATH . "/var";

    const CACHE_CONFIG_INFO_FILENAME = "ClientConfigInfo.cache";

    const CACHE_CONFIG_FILENAME = "clientConfig.cache";

}