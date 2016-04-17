<?php
namespace Initially\Rpc\Core\Config\Client;

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
     * Client constructor.
     * @param string $url
     * @param string $interface
     */
    public function __construct($url, $interface)
    {
        $this->url = $url;
        $this->interface = $interface;
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

}