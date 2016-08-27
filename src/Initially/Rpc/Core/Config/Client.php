<?php
namespace Initially\Rpc\Core\Config;

class Client
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $interface;

    /**
     * @var string
     */
    private $transport;

    /**
     * Client constructor.
     * @param string $url
     * @param string $interface
     * @param string $transport
     */
    public function __construct($url, $interface, $transport)
    {
        $this->url = $url;
        $this->interface = $interface;
        $this->transport = $transport;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * @param $interface
     */
    public function setInterface($interface)
    {
        $this->interface = $interface;
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