<?php
namespace Initially\Rpc\Core\Web;

use Initially\Rpc\Core\Config\Factory;
use Initially\Rpc\Exception\InitiallyRpcException;
use ReflectionClass;
use ReflectionException;

class Control
{

    /**
     * @var string
     */
    private $templateDir;

    /**
     * Control constructor.
     */
    public function __construct()
    {
        $this->templateDir = __DIR__ . "/template";
    }

    /**
     * List action
     */
    public function listAction()
    {
        $configs = Factory::getServerAll();
        include $this->templateDir . "/listView.php";
    }

    /**
     * Detail action
     * @return bool
     */
    public function detailAction()
    {
        $interface = isset($_GET["interface"]) ? trim($_GET["interface"]) : "";
        if (empty($interface)) {
            return $this->error();
        }

        try {
            $config = Factory::getServer($interface);
            $reflectionClass = new ReflectionClass($interface);
        } catch (InitiallyRpcException $e) {
            return $this->error();
        } catch (ReflectionException $e) {
            return $this->error();
        }

        include $this->templateDir . "/detailView.php";
    }

    /**
     * @param string $tip
     * @return bool
     */
    private function error($tip = "")
    {
        echo "error";
        return false;
    }

}