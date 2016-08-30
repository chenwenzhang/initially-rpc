<?php
namespace InitiallyDemo\Vendor\Service;

use InitiallyDemo\Common\Domain\Entity\Demo1;
use InitiallyDemo\Common\Interfaces\Demo1ServiceInterface;

class Demo1Service implements Demo1ServiceInterface
{

    /**
     * 根据 ID 获取Demo信息
     *
     * @param int $id
     * @return Demo1
     */
    public function queryById(int $id): Demo1
    {
        $demo = new Demo1();
        $demo->setId(100);
        $demo->setName("mm");
        return $demo;
    }

    /**
     * 返回两个数相加的结果
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }

}