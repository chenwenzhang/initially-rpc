<?php
namespace Initially\Rpc\Protocol;

use Initially\Rpc\Protocol\ClientInvoker;
use Initially\Rpc\Protocol\ServerInvoker;

class Protocol
{

    /**
     * @param object $target
     * @return ServerInvoker
     */
    public function export($target)
    {
        $invoker = new ServerInvoker($target);
        return $invoker;
    }

    /**
     * @param string $interface
     * @return ClientInvoker
     */
    public function refer($interface)
    {
        $invoker = new ClientInvoker($interface);
        return $invoker;
    }

}