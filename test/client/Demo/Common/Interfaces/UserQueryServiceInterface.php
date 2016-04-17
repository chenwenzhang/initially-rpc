<?php
namespace InitiallyDemo\Common\Interfaces;

use InitiallyDemo\Common\Domain\VO\UserVO;

interface UserQueryServiceInterface
{

    /**
     * @param $id
     * @return UserVO
     */
    public function queryById($id);

    /**
     * Add
     * @param int $a
     * @param int $b
     * @return int
     */
    public function add($a, $b);

}