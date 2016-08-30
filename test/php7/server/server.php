<?php
include __DIR__ . "/../../../vendor/autoload.php";

$server = new \Initially\Rpc\Core\Engine\Server(__DIR__ . "/server.json");
$server->handle();