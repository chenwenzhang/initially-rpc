<?php
namespace InitiallyDemo\Common\Interfaces;

use InitiallyDemo\Common\Domain\Entity\Demo1;

interface Demo1ServiceInterface
{

    /**
     * 根据 ID 获取Demo信息
     *
     * @param int $id
     * @return Demo1
     */
    public function queryById(int $id): Demo1;

    /**
     * 返回两个数相加的结果
     *
     * @param int $a
     * @param int $b
     * @return int
     */
    public function add(int $a, int $b): int;

}