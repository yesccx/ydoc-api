<?php

/*
 * Base Logic
 *
 * @Created: 2020-06-18 22:18:24
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\extend;

use app\extend\session\AppSession;
use app\traits\common\EntityMake;

abstract class BaseLogic {

    use EntityMake;

    /**
     * 用户uid
     *
     * @var int
     */
    protected $uid = 0;

    public function __construct() {
        $this->uid = AppSession::make()->getUid();
    }

    /**
     * 切换上下文用户
     *
     * @param int $uid
     * @return $this
     */
    public function setContextUser($uid) {
        $this->uid = $uid;
        return $this;
    }

}
