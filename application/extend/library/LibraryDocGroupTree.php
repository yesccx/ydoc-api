<?php

/*
 * 文档分组树相关工具
 *
 * @Created: 2020-06-25 19:57:09
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\library;

class LibraryDocGroupTree {

    /**
     * 构建文档分组树（递归）
     *
     * @param int $pid 局部根级分组id
     * @param array $groupCollection 分组集合
     * @return array 分组树
     */
    public static function buildTree($pid = 0, $groupCollection = []) {
        if (empty($groupCollection)) {
            return [];
        }

        // 筛选出当前分组与子分组
        $topGroupCollection = [];
        $resGroupCollection = [];
        foreach ($groupCollection as $group) {
            if ($group['pid'] == $pid) {
                $topGroupCollection[] = $group;
            } else {
                $resGroupCollection[] = $group;
            }
        }

        // 递归获取子分组
        foreach ($topGroupCollection as &$group) {
            $children = self::buildTree($group['id'], $resGroupCollection);
            if (!empty($children)) {
                $group['children'] = $children;
            }
        }

        return $topGroupCollection;
    }

}