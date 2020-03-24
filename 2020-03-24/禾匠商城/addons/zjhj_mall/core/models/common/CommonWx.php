<?php

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common;


class CommonWx
{
    public function wxDefaultTitle()
    {
        return [
            [
                'url' => 'pages/share/index',
                'title' => '分销中心',
            ],
            [
                'url' => 'pages/user/user',
                'title' => '个人中心',
            ],
            [
                'url' => 'pages/pt/index/index',
                'title' => '拼团',
            ],
            [
                'url' => 'pages/book/index/index',
                'title' => '预约',
            ],
            [
                'url' => 'pages/fxhb/open/open',
                'title' => '拆红包',
            ],
            [
                'url' => 'mch/shop-list/shop-list',
                'title' => '好店推荐',
            ],
            [
                'url' => 'pages/cart/cart',
                'title' => '购物车',
            ],
            [
                'url' => 'pages/cat/cat',
                'title' => '分类',
            ],
            [
                'url' => 'pages/coupon/coupon',
                'title' => '我的优惠券',
            ],
            [
                'url' => 'pages/coupon-list/coupon-list',
                'title' => '领券中心',
            ],
            [
                'url' => 'pages/favorite/favorite',
                'title' => '我的收藏',
            ],
            [
                'url' => 'mch/m/myshop/myshop',
                'title' => '我的店铺',
            ],
            [
                'url' => 'mch/shop/shop',
                'title' => '店铺首页',
            ],
            [
                'url' => 'pages/integral-mall/index/index',
                'title' => '积分商城',
            ],
            [
                'url' => 'pages/topic-list/topic-list',
                'title' => '专题',
            ],
            [
                'url' => 'pages/topic/topic',
                'title' => '专题详情',
            ],
        ];
    }
}
