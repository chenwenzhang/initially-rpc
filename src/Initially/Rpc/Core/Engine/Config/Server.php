<?php
namespace Initially\Rpc\Core\Engine\Config;

class Server
{

    /**
     * @var array
     */
    private $services = array();

    /**
     * @var string
     */
    private $transport;

    /**
     * @param $interface
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

}