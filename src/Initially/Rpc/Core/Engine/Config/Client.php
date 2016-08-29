<?php
namespace Initially\Rpc\Core\Engine\Config;

class Client
{

    /**
     * @var string
     */
    private $url = "";

    /**
     * @var string
     */
    private $transport = "";

    /**
     * @var string
     */
    private $proxyRootDir = "";

    /**
     * @var array
     */
    private $services = array();

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param string $transport
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return string
     */
    public function getProxyRootDir()
    {
        return $this->proxyRootDir;
    }

    /**
     * @param string $proxyRootDir
     */
    public function setProxyRootDir($proxyRootDir)
    {
        $this->proxyRootDir = $proxyRootDir;
    }

    /**
     * @param string $interface
     * @return Service
     */
    public function getService($interface)
    {
        return $this->services[$interface];
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param Service $service
     */
    public function addService(Service $service)
    {
        $this->services[$service->getInterface()] = $service;
    }

}