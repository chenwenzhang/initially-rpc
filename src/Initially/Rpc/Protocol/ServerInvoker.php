<?php
namespace Initially\Rpc\Protocol;

use Initially\Rpc\Core\Engine\ServerApplication;
use Initially\Rpc\Exception\InitiallyRpcException;
use Initially\Rpc\Transport\Formatter;
use Initially\Rpc\Transport\Response;

class ServerInvoker implements Invoker
{

    /**
     * @var object
     */
    private $target;

    /**
     * ServerInvoker constructor.
     * @param object $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * @param Invocation $invocation
     * @return mixed
     * @throws InitiallyRpcException
     */
    public function invoke(Invocation $invocation)
    {
        $methodName = $invocation->getMethodName();
        $arguments = $invocation->getArguments();
        if (!method_exists($this->target, $methodName)) {
            throw new InitiallyRpcException("method not exists");
        } elseif (empty($arguments)) {
            $result = call_user_func(array($this->target, $methodName));
        } else {
            $result = call_user_func_array(array($this->target, $methodName), $arguments);
        }

        $response = new Response();
        $response->setResult($result);
        ServerApplication::getInstance()->getTransport()->reply($response);
    }

}