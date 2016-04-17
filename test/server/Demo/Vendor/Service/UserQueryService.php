<?php
namespace InitiallyDemo\Vendor\Service;

use InitiallyDemo\Common\Domain\VO\UserVO;
use InitiallyDemo\Common\Interfaces\UserQueryServiceInterface;

class UserQueryService implements UserQueryServiceInterface
{

    /**
     * @param $id
     * @return UserVO
     */
    public function queryById($id)
    {
        $userVO = new UserVO();
        $userVO->setId(1000000);
        $userVO->setUsername("smile.cwz");
        return $userVO;
    }

    /**
     * @param int $a
     * @param int $b
     * @return int
     */
    public function add($a, $b)
    {
        return $a + $b;
    }

}