<?php
namespace Initially\Rpc\Protocol;

use Initially\Rpc\Transport\Request;

class ClientInvoker implements Invoker
{

    /**
     * @var string
     */
    private $type;

    /**
     * ClientInvoker constructor.
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @param Invocation $invocation
     * @return mixed
     */
    public function invoke(Invocation $invocation)
    {
        $request = new Request();
        $request->setType($this->type);
        $request->setMethodName($invocation->getMethodName());
        $request->setArguments($invocation->getArguments());

    }

}