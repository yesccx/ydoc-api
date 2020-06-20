<?php

/*
 * AppPageinate
 * PS: 分页器
 *
 * @Created: 2020-06-20 09:09:27
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\common;

use app\traits\common\EntityMake;

class AppPagination {

    use EntityMake;

    /**
     * 默认每页数量
     */
    const DEFAULT_PAGE_SIZE = 15;

    /**
     * 最大每页数量
     */
    const MAX_PAGE_SIZE = 50;

    /**
     * 当前页
     *
     * @var int
     */
    public $pageNum = 0;

    /**
     * 每页数量
     *
     * @var int
     */
    public $pageSize = 0;

    /**
     * 分页排序字段
     *
     * @var string
     */
    public $pageOrderField = 'id';

    /**
     * 分页排序方式
     * PS: 大于等于0降序，小于0升序
     *
     * @var string
     */
    public $pageOrder = -1;

    /**
     * 分页结果
     *
     * @var array
     */
    public $pageData = [
        'list'      => [],
        'total'     => 0,
        'page_size' => self::DEFAULT_PAGE_SIZE,
        'page_num'  => 1,
    ];

    public function __construct($pageNum = null, $pageSize = null, $pageOrderField = null, $pageOrder = null) {
        // 分页相关参数自动从请求中获取
        $pageNum = $pageNum ?: AppRequest::input('page_num/d', 1);
        $pageSize = $pageSize ?: AppRequest::input('page_size/d', 15);
        $pageOrderField = $pageOrderField ?: AppRequest::input('page_order_field/s', 'id');
        $pageOrder = $pageOrder ?: AppRequest::input('page_order/d', -1);

        $this->pageOrderField = $pageOrderField;
        $this->pageOrder = min(max($pageOrder, -1), 1);
        $this->pageNum = max($pageNum, 1);
        $this->pageSize = min($pageSize ?: self::DEFAULT_PAGE_SIZE, self::MAX_PAGE_SIZE);
    }

    /**
     * 设置分页结果数据
     *
     * @param array $pageData
     * @return $this
     */
    public function setPageData($pageData = []) {
        $this->pageData = array_merge($this->pageData, $pageData);
        return $this;
    }

    /**
     * list map
     */
    public function map(callable $callback) {
        return array_map($callback, $this->pageData['list']);
    }

    /**
     * 按指定格式输出分页结果
     *
     * @return array
     */
    public function toArray() {
        return $this->pageData;
    }

    /**
     * 按指定格式输出空结果
     *
     * @return array
     */
    public function toEmpty() {
        return AppPagination::make()->toArray();
    }

}