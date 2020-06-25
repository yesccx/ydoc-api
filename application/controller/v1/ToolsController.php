<?php

/*
 * 工具相关 Controller
 *
 * @Created: 2020-06-24 18:56:27
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1;

use app\extend\common\AppQuery;
use app\kernel\base\AppBaseController;
use app\service\UserService;

class ToolsController extends AppBaseController {

    /**
     * 获取用户集合
     */
    public function memberCollect() {
        $limit = $this->input('limit/d', 10);
        $searchKey = $this->input('search_key/s', '');

        // 查询条件
        $query = AppQuery::make();
        $query->field('id,account,avatar,nickname')
            ->when($searchKey, function ($squery) use ($searchKey) {
                $squery->whereLike('account', "%{$searchKey}%");
            });

        $userCollect = UserService::getUserCollect($limit, $query);

        return $this->responseData($userCollect);
    }

}