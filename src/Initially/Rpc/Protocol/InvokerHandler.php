<?php
namespace Initially\Rpc\Protocol;

class InvokerHandler
{

    /**
     * @var Invoker
     */
    private $invoker;

    /**
     * InvokerHandler constructor.
     * @param Invoker $invoker
     */
    public function __construct(Invoker $invoker)
    {
        $this->invoker = $invoker;
    }

    /**
     * @param string $methodName
     * @param array $arguments
     * @return mixed
     */
    public function invoke($methodName, array $arguments)
    {
        $invocation = new Invocation($methodName, $arguments);
        return $this->invoker->invoke($invocation);
    }

}