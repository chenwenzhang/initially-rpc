<?php
namespace Initially\Rpc;

use Initially\Rpc\Protocol\ClientInvoker;
use Initially\Rpc\Protocol\ServerInvoker;

class Protocol
{

    public function export($target)
    {
        $invoker = new ServerInvoker($target);
        return $invoker;
    }

    public function refer($type)
    {
        $invoker = new ClientInvoker($type);
        return $invoker;
    }

}