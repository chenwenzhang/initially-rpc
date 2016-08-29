<?php
namespace Initially\Rpc\Proxy;

class PHP5Template extends TemplateAbstract
{

    /**
     * Get method template
     *
     * @return string
     */
    protected function _getMethodTemplate()
    {
        return include __DIR__ . "/template/method-5.php";
    }

}