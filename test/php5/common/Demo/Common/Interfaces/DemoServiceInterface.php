<?php
namespace InitiallyDemo\Common\Interfaces;

use InitiallyDemo\Common\Domain\Entity\Demo;

interface DemoServiceInterface
{

    /**
     * @param int $id
     * @return Demo
     */
    public function queryById($id);

    /**
     * add
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    public function add($a, $b);

}