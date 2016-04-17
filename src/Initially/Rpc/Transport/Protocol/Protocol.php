<?php
namespace Initially\Rpc\Transport\Protocol;

interface Protocol
{

    /**
     * @param string $uri
     * @param string $data
     * @return string
     */
    public function sendData($uri, $data);

}