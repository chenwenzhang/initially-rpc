<?php
namespace Initially\Rpc\Core\Engine;

use Initially\Rpc\Core\Config\Loader as ConfigLoader;
use Initially\Rpc\Core\Support\Registry;
use Initially\Rpc\Exception\InitiallyRpcException;
use Initially\Rpc\Proxy\Builder;
use Initially\Rpc\Transport\Transport;

class ClientApplication implements Application
{

    /**
     * @var string
     */
    private $configFile;

    /**
     * ClientApplication constructor.
     * @param $configFile
     * @throws InitiallyRpcException
     */
    public function __construct($configFile)
    {
        $this->configFile = realpath($configFile);
        if ($this->configFile === false) {
            throw new InitiallyRpcException();
        }
    }

    /**
     * @throws InitiallyRpcException
     */
    public function run()
    {
        // load client config
        ConfigLoader::loadClient($this->configFile);
        // register transport
        Registry::set("transport", function () {
            return new Transport();
        });

        if (null !== ($clientConfigFileInfo = Util::getClientConfigFileInfo()) &&
            Util::isFileModify($clientConfigFileInfo["file"], $clientConfigFileInfo["last"])) {

        }

        $this->recordClientConfigFileInfo();
    }

    private function recordClientConfigFileInfo()
    {
        $arr = array(
            "file" => $this->configFile,
            "last" => filemtime($this->configFile)
        );
        Util::recordClientConfigFileInfo($arr);
    }

}