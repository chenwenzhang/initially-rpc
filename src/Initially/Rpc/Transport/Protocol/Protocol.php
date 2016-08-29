<?php
namespace Initially\Rpc\Transport\Protocol;

use Initially\Rpc\Transport\Response;

interface Protocol
{

    /**
     * Http protocol
     */
    const HTTP = "http";

    /**
     * @param string $uri
     * @param string $data
     * @return string
     */
    public function sendData($uri, $data);

    /**
     * @return Response
     */
    public function receive();

    /**
     * @param Response $response
     */
    public function reply(Response $response);

    /**
     * Handle
     */
    public function handle();

}