<?php
namespace InitiallyDemo\Vendor\Service;

use InitiallyDemo\Common\Domain\Entity\Demo;
use InitiallyDemo\Common\Interfaces\DemoServiceInterface;

class DemoService implements DemoServiceInterface
{

    /**
     * @param int $id
     * @return Demo
     */
    public function queryById($id)
    {
        $demo = new Demo();
        $demo->setId(10000);
        $demo->setName("smile");
        return $demo;
    }

    /**
     * add
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    public function add($a, $b)
    {
        return $a + $b;
    }

}