<?php
include __DIR__ . "/../../vendor/autoload.php";

$initiallyServerApplication = new \Initially\Rpc\Core\Engine\ServerApplication("server.json");
$initiallyServerApplication->run();