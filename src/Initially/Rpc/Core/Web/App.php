<?php
namespace Initially\Rpc\Core\Web;

class App
{

    /**
     * Web app handle
     */
    public function handle()
    {
        $action = isset($_GET["action"]) ? trim($_GET["action"]) : "list";
        $control = new Control();
        switch ($action) {
            case "detail":
                $control->detailAction();
                break;
            default:
                $control->listAction();
        }
    }

}