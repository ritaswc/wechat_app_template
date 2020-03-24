<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14
 * Time: 9:22
 */
defined('YII_ENV') or exit('Access Denied');
$urlManager = Yii::$app->urlManager;
$this->title = '小程序页面';
$list = [
    [
        'name' => '商城首页',
        'route' => 'pages/index/index',
        'params' => [],
    ],
    [
        'name' => '分类',
        'route' => 'pages/cat/cat',
        'params' => [],
    ],
    [
        'name' => '购物车',
        'route' => 'pages/cart/cart',
        'params' => [],
    ],
    [
        'name' => '用户中心',
        'route' => 'pages/user/user',
        'params' => [],
    ],
    [
        'name' => '会员中心',
        'route' => 'pages/member/member',
        'params' => [],
    ],
    [
        'name' => '商品列表',
        'route' => 'pages/list/list',
        'params' => [
            [
                'name' => 'cat_id',
                'desc' => '分类id',
            ],
        ],
    ],
    [
        'name' => '搜索页',
        'route' => 'pages/search/search',
        'params' => [],
    ],
    [
        'name' => '商品详情',
        'route' => 'pages/goods/goods',
        'params' => [
            [
                'name' => 'id',
                'desc' => '商品id',
            ],
        ],
    ],
    [
        'name' => '订单列表',
        'route' => 'pages/order/order',
        'params' => [
            [
                'name' => 'status',
                'desc' => '订单状态[所有订单=-1,待付款=0,待发货=1,待收货=2,已完成=3,售后=4]',

            ],
        ],
    ],
    [
        'name' => '我的收藏',
        'route' => 'pages/favorite/favorite',
        'params' => [],
    ],
    [
        'name' => '分销中心',
        'route' => 'pages/share/index',
        'params' => [],
    ],
    [
        'name' => '关于我们',
        'route' => 'pages/article-detail/article-detail',
        'params' => [
            [
                'name' => 'id=about_us',
                'desc' => '',
            ],
        ],
    ],
    [
        'name' => '服务中心列表',
        'route' => 'pages/article-list/article-list',
        'params' => [],
    ],
    [
        'name' => '服务中心详情',
        'route' => 'pages/article-detail/article-detail',
        'params' => [
            [
                'name' => 'id=2',
                'desc' => '',
            ],
        ],
    ],
    [
        'name' => '我的优惠券',
        'route' => 'pages/coupon/coupon',
        'params' => [],
    ],
    [
        'name' => '门店列表',
        'route' => 'pages/shop/shop',
        'params' => [],
    ],
    [
        'name' => '视频列表',
        'route' => 'pages/video/video-list',
        'params' => [],
    ],
    [
        'name' => '领券中心',
        'route' => 'pages/coupon-list/coupon-list',
        'params' => [],
    ],
    [
        'name' => '专题列表',
        'route' => 'pages/topic-list/topic-list',
        'params' => [
            [
                'name' => 'type',
                'desc' => '专题类型',
            ],
        ],
    ],
    [
        'name' => '专题详情',
        'route' => 'pages/topic/topic',
        'params' => [
            [
                'name' => 'id',
                'desc' => '专题id',
            ],
        ],
    ],
    [
        'name' => '秒杀',
        'route' => 'pages/miaosha/miaosha',
        'params' => [],
    ],
    [
        'name' => '小程序内嵌网页',
        'route' => 'pages/web/web',
        'params' => [
            [
                'name' => 'url',
                'desc' => '网址(网址必须UrlEncode，域名必须在小程序官方后台设置业务域名)',
            ],
        ],
    ],
    [
        'name' => '门店详情',
        'route' => 'pages/shop-detail/shop-detail',
        'params' => [
            [
                'name' => 'shop_id',
                'desc' => '门店id',
            ],
        ],
    ],
    [
        'name' => '我的卡券',
        'route' => 'pages/card/card',
        'params' => [

            [
                'name' => 'status',
                'desc' => '卡券状态(选填)[已失效=2]',
            ],
        ],
    ],
    [
        'name' => '拼团首页',
        'route' => 'pages/pt/index/index',
        'params' => [],
    ],
    [
        'name' => '拼团商品详情',
        'route' => 'pages/pt/details/details',
        'params' => [
            [
                'name' => 'gid',
                'desc' => '拼团商品id',
            ],
        ],
    ],
    [
        'name' => '拼团订单列表',
        'route' => 'pages/pt/order/order',
        'params' => [
            [
                'name' => 'status',
                'desc' => '订单状态(选填)[全部=-1,待付款=0,拼团中=1,拼团成功=2,拼团失败=3,售后=4]',
            ],
        ],
    ],
    [
        'name' => '预约首页',
        'route' => 'pages/book/index/index',
        'params' => [],
    ],
    [
        'name' => '预约详情',
        'route' => 'pages/book/details/details',
        'params' => [
            [
                'name' => 'id',
                'desc' => '预约id',
            ],
        ],
    ],
    [
        'name' => '我的预约列表',
        'route' => 'pages/book/order/order',
        'params' => [
            [
                'name' => 'status',
                'desc' => '状态(选填)[待支付=0,待使用=1,已使用=2,退款=3]',
            ],
        ],
    ],
    [
        'name' => '九宫格抽奖',
        'route' => 'pond/pond/pond',
        'params' => [],
    ],
    [
        'name' => '签到',
        'route' => 'pages/integral-mall/register/index',
        'params' => [],
    ],
    [
        'name' => '积分商城',
        'route' => 'pages/integral-mall/index/index',
        'params' => [],
    ],
    [
        'name' => '入驻商',
        'route' => 'mch/m/myshop/myshop',
        'params' => [],
    ],
    [
        'name' => '快速购买',
        'route' => 'pages/quick-purchase/index/index',
        'params' => [],
    ],
    [
        'name' => '多商户店铺',
        'route' => 'mch/shop/shop',
        'params' => [
            [
                'name' => 'mch_id',
                'desc' => '入驻商户ID',
            ],
        ],
    ],
    [
        'name' => '刮刮卡',
        'route' => 'scratch/index/index',
        'params' => [],
    ],
    [
        'name' => '砍价',
        'route' => 'bargain/list/list',
        'params' => [],
    ],
    [
        'name' => '砍价商品详情',
        'route' => 'bargain/goods/goods',
        'params' => [
            [
                'name' => 'goods_id',
                'desc' => '砍价商品列表的商品ID'
            ]
        ],
    ],
    [
        'name' => '抽奖',
        'route' => 'lottery/index/index',
        'params' => [],
    ],
    [
        'name' => '抽奖商品详情',
        'route' => 'lottery/goods/goods',
        'params' => [
            [
                'name' => 'id',
                'desc' => '抽奖列表ID'
            ]
        ],
    ],
    [
        'name' => '步数宝',
        'route' => 'step/index/index',
        'params' => [],
    ],
    [
        'name' => 'DIY页面',
        'route' => 'pages/index/index',
        'params' => [
            [
                'name' => 'page_id',
                'desc' => '自定义页面页面ID'
            ]
        ],
    ],
];
?>
<style>
    .page-list > li {
        margin-bottom: 1rem;
    }
</style>
<div class="alert alert-warning rounded-0">
    页面参数使用示例：订单列表，需要查询状态是已完成的订单，则拼接参数后的路径为<span class="text-danger">pages/order/order?status=3</span>
</div>
<div class="panel">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body">
        <ul class="page-list">
            <?php foreach ($list as $item) : ?>
                <li>
                    <div class="text-primary"><?= $item['name'] ?></div>
                    <div>
                        <span>路径：</span>
                        <span class="text-primary"><?= $item['route'] ?></span>
                    </div>
                    <?php if (is_array($item['params']) && count($item['params'])) : ?>
                        <div>
                            <span>参数：</span>
                            <?php foreach ($item['params'] as $i => $p) : ?>
                                <span class="text-primary"><?= $p['name'] ?></span>
                                <span class="text-muted mr-3"><?= $p['desc'] ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>