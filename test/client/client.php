<?php
include __DIR__ . "/../../vendor/autoload.php";

$initiallyClientApplication = new \Initially\Rpc\Core\Engine\ClientApplication("client.json");
$initiallyClientApplication->run();

$userQueryService = new \Initially\Rpc\Api\InitiallyDemo\Common\Interfaces\UserQueryService();
var_dump($userQueryService->add(1, 2));

$userVO = $userQueryService->queryById(123);
var_dump($userVO instanceof \InitiallyDemo\Common\Domain\VO\UserVO);
var_dump($userVO->getId());
var_dump($userVO->getUsername());