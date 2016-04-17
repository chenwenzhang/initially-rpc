<?php
namespace Initially\Rpc\Core\Config\Server;

class Server
{

    /**
     * @var string
     */
    private $interface;

    /**
     * @var string
     */
    private $reference;

    /**
     * Server constructor.
     * @param string $interface
     * @param string $reference
     */
    public function __construct($interface, $reference)
    {
        $this->interface = $interface;
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * @param string $interface
     */
    public function setInterface($interface)
    {
        $this->interface = $interface;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

}