<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common\admin\order;


class CommonOrderSearch
{
    /**
     * 持续补充
     * * 注意！注意！注意！：订单表别名必须为o | 用户表别名必须为 u | 商品表别名必须为 g
     */

    /**
     * 相同的搜索
     */
    public function search($query, $page)
    {
        if (isset($page->user_id) && $page->user_id) {//查找指定用户的
            $query->andWhere([
                'o.user_id' => $page->user_id,
            ]);
        }

        if (isset($page->clerk_id) && $page->clerk_id) {//查找指定核销员的订单
            $query->andWhere([
                'o.clerk_id' => $page->clerk_id,
            ]);
        }
        if (isset($page->shop_id) && $page->shop_id) {//查找指定门店的订单
            $query->andWhere([
                'o.shop_id' => $page->shop_id,
            ]);
        }

        if (isset($page->parent_id) && $page->parent_id) {
            $query->andWhere(['o.parent_id' => $page->parent_id]);
        }

        if (isset($page->date_start) && $page->date_start) {
            $query->andWhere(['>=', 'o.addtime', strtotime($page->date_start)]);
        }
        if (isset($page->date_end) && $page->date_end) {
            $query->andWhere(['<=', 'o.addtime', strtotime($page->date_end)]);
        }

        if (isset($page->platform) && ($page->platform == 1 || $page->platform == 0)) {
            $query->andWhere(['u.platform' => $page->platform]);
        }

        if (isset($page->is_offline) && $page->is_offline) {
            $query->andWhere(['o.is_offline' => $page->is_offline]);
        }

        return $query;
    }

    /**
     * 关键字订单搜索
     * @param $query
     * @param $keywordType 搜索类型 1.订单号 2.用户 3.收货人 4.用户ID 5.商品名称 6.收件人电话
     * @param $keyword 关键字
     * @return mixed
     */
    public function keyword($query, $keywordType, $keyword)
    {
        if (isset($keyword) && $keyword) {
            switch ($keywordType) {
                case 1:
                    $query->andWhere(['like', 'o.order_no', $keyword]);
                    break;
                case 2:
                    $query->andWhere(['like', 'u.nickname', $keyword]);
                    break;
                case 3:
                    $query->andWhere(['like', 'o.name', $keyword]);
                    break;
                case 4:
                    $query->andWhere(['u.id' => $keyword]);
                    break;
                case 5:
                    $query->andWhere(['like', 'g.name', $keyword]);
                    break;
                case 6:
                    $query->andWhere(['like', 'o.mobile', $keyword]);
                    break;
                default:
                    break;
            }
        }

        return $query;
    }
}