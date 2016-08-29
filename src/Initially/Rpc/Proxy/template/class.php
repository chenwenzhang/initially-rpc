<?php
$__t_c = <<< TPL
<?php
namespace __NAMESPACE__;

use Initially\Rpc\Protocol\InvokerHandler;
use Initially\Rpc\Protocol\Protocol;
__USE_LIST__

__CLASS_DOC__
class __CLASS_NAME__ implements __INTERFACE_NAME__
{

    /**
     * @var Protocol
     */
    private \$protocol;

    /**
     * @var InvokerHandler
     */
    private \$handle;

    /**
     * @var string
     */
    private \$interface;

    /**
     * Construct
     */
    public function __construct()
    {
        \$this->interface = "__INTERFACE_NAME_FORMAT__";
        \$this->protocol = new Protocol();
        \$this->handle = new InvokerHandler(\$this->protocol->refer(\$this->interface));
    }

    __METHOD__
}
TPL;

return $__t_c;