<?php
namespace Initially\Rpc\Core\Support;

define("INITIALLY_RPC_ROOT", dirname(dirname(dirname(dirname(__DIR__)))));

class Constants
{

    const ROOT_PATH = INITIALLY_RPC_ROOT;

    const VAR_PATH = INITIALLY_RPC_ROOT . "/var";

    const CACHE_CONFIG_INFO_FILENAME = "ClientConfigInfo.cache";

    const PROXY_PREFIX_NAMESPACE = "Initially\\Rpc\\Api";

}