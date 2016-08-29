<?php
include __DIR__ . "/../../vendor/autoload.php";

$initiallyClientApplication = new \Initially\Rpc\Core\Engine\Client("client.json");


$demoService = new \Initially\Rpc\Api\InitiallyDemo\Common\Interfaces\DemoService();
$addResult = $demoService->add(1, 2);
var_dump($addResult);

$demo = $demoService->queryById(123);
var_dump($demo->getId());
var_dump($demo->getName());