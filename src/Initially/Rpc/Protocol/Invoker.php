<?php
namespace Initially\Rpc\Protocol;

interface Invoker
{

    /**
     * @param Invocation $invocation
     * @return mixed
     */
    public function invoke(Invocation $invocation);

}