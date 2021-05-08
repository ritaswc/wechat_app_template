<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */


namespace app\modules\mch\models;

use app\models\DiyPage;

/**
 * @property \app\models\Area $area
 */
class PickLinkForm
{
    public $userAuth;


    /**
     * 小程序菜单跳转链接
     * @return mixed|string
     */
    public function getPickLink()
    {
        $link = $this->link();

        $pickLink = $this->resetPickLink($link, $this->userAuth);

        return json_encode($pickLink);
    }

    /**
     * 小程序底部导航链接
     */
    public function getNavPickLink()
    {
        $navLink = $this->navLink();

        $navPickLink = $this->resetPickLink($navLink, $this->userAuth);

        return json_encode($navPickLink);
    }

    /**
     * 去除账号没有权限的链接
     * @param $link
     * @param $userAuth
     * @return mixed
     */
    public function resetPickLink($link, $userAuth)
    {
        $newData = [];
        foreach ($link as $k => $item) {
            if (isset($item['sign']) == false || in_array($item['sign'], $userAuth) == true) {
                $newData[] = $item;
            }
        }

        return $newData;
    }

    /**
     * 导航链接
     * @return array
     */
    public function link()
    {
        $list = [
            [
                'name' => "商城首页",
                'link' => "/pages/index/index",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "分类",
                'link' => "/pages/cat/cat",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "cat_id",
                        'value' => "",
                        'desc' => "cat_id请填写在商品分类中相关分类的ID"
                    ]
                ]
            ],
            [
                'name' => "购物车",
                'link' => "/pages/cart/cart",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "会员中心",
                'link' => "/pages/member/member",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "用户中心",
                'link' => "/pages/user/user",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "商品列表",
                'link' => "/pages/list/list",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "cat_id",
                        'value' => "",
                        'desc' => "cat_id请填写在商品分类中相关分类的ID"
                    ]
                ]
            ],
            [
                'name' => "商品详情",
                'link' => "/pages/goods/goods",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "id",
                        'value' => "",
                        'desc' => "id请填写在商品列表中相关商品的ID"
                    ]
                ]
            ],
            [
                'name' => "所有订单",
                'link' => "/pages/order/order?status=-1",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "待付款订单",
                'link' => "/pages/order/order?status=0",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "待发货订单",
                'link' => "/pages/order/order?status=1",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "待收货订单",
                'link' => "/pages/order/order?status=2",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "已完成订单",
                'link' => "/pages/order/order?status=3",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "售后订单",
                'link' => "/pages/order/order?status=4",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => "share",
                'name' => "分销中心",
                'link' => "/pages/share/index",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'coupon',
                'name' => "我的优惠券",
                'link' => "/pages/coupon/coupon",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "我的收藏",
                'link' => "/pages/favorite/favorite",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "关于我们",
                'link' => "/pages/article-detail/article-detail?id=about_us",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "服务中心",
                'link' => "/pages/article-list/article-list?id=2",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'video',
                'name' => "视频专区",
                'link' => "/pages/video/video-list",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'topic',
                'name' => "专题列表",
                'link' => "/pages/topic-list/topic-list",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "type",
                        'value' => "",
                        'desc' => "type请填写在专题分类中的ID"
                    ]
                ]
            ],
            [
                'sign' => 'topic',
                'name' => "专题详情",
                'link' => "/pages/topic/topic",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "id",
                        'value' => "",
                        'desc' => "id请填写在专题列表中相关专题的ID"
                    ]
                ]
            ],
            [
                'sign' => 'coupon',
                'name' => "领券中心",
                'link' => "/pages/coupon-list/coupon-list",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "小程序",
                'link' => "/",
                'open_type' => "wxapp",
                'params' => [
                    [
                        'key' => "appId",
                        'value' => "",
                        'desc' => "要打开的小程序 appId"
                    ],
                    [
                        'key' => "path",
                        'value' => "",
                        'desc' => "打开的页面路径，如pages/index/index，开头请勿加“/”"
                    ],
                ]
            ],
            [
                'sign' => 'miaosha',
                'name' => "整点秒杀（需先安装插件）",
                'link' => "/pages/miaosha/miaosha",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'miaosha',
                'name' => '我的秒杀',
                'link' => '/pages/miaosha/order/order',
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "网页链接",
                'link' => "/pages/web/web",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "url",
                        'value' => "",
                        'desc' => "打开的网页链接（注：域名必须已在微信官方小程序平台设置业务域名）",
                    ]
                ],
            ],
            [
                'name' => "门店列表",
                'link' => "/pages/shop/shop",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'pintuan',
                'name' => "拼团",
                'link' => "/pages/pt/index/index",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'pintuan',
                'name' => "我的拼团",
                'link' => "/pages/pt/order/order",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'pintuan',
                'name' => "拼团详情",
                'link' => "/pages/pt/details/details",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "gid",
                        'value' => "",
                        'desc' => "gid请填写拼团商品列表的商品ID"
                    ]
                ],
            ],
            [
                'sign' => 'pintuan',
                'name' => "拼团分类",
                'link' => "/pages/pt/index/index",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "cid",
                        'value' => "",
                        'desc' => "cid请填写拼团商品列表的分类ID，为空则跳转地址为 拼团"
                    ]
                ],
            ],
            [
                'sign' => 'book',
                'name' => "预约详情",
                'link' => "/pages/book/details/details",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "id",
                        'value' => "",
                        'desc' => "ID请填写预约商品列表的商品ID"
                    ]
                ],
            ],
            [
                'sign' => 'book',
                'name' => "预约分类",
                'link' => "/pages/book/index/index",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "cid",
                        'value' => "",
                        'desc' => "cid请填写预约商品列表的分类ID,为空则跳转地址为 预约"
                    ]
                ],
            ],
            [
                'sign' => 'book',
                'name' => "我的预约",
                'link' => "/pages/book/order/order",
                'open_type' => "navigate",
                'params' => [],
            ],
            [
                'name' => "快速购买",
                'link' => "/pages/quick-purchase/index/index",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'fxhb',
                'name' => "裂变拆红包",
                'link' => "/pages/fxhb/open/open",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "充值",
                'link' => "/pages/recharge/recharge",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'mch',
                'name' => "好店推荐",
                'link' => "/mch/shop-list/shop-list",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'mch',
                'name' => "多商户店铺",
                'link' => "/mch/shop/shop",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "mch_id",
                        'value' => "",
                        'desc' => "mch_id 请填写入驻商户ID",
                        'required' => 'required'
                    ]
                ],
            ],
            [
                'sign' => 'mch',
                'name' => "入驻商",
                'link' => "/mch/m/myshop/myshop",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'integralmall',
                'name' => "积分商城",
                'link' => "/pages/integral-mall/index/index",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'integralmall',
                'name' => "签到",
                'link' => "/pages/integral-mall/register/index",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "搜索页",
                'link' => "/pages/search/search",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'pond',
                'name' => "九宫格抽奖",
                'link' => "/pond/pond/pond",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'scratch',
                'name' => "刮刮卡",
                'link' => "/scratch/index/index",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'name' => "我的订单",
                'link' => "/pages/order/order",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => "status",
                        'value' => 0,
                        'desc' => "status 请填写订单列表状态, 为空则跳转为 待付款",
                    ]
                ],
            ],
            [
                'sign' => 'bargain',
                'name' => "砍价",
                'link' => "/bargain/list/list",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'bargain',
                'name' => "砍价商品详情",
                'link' => "/bargain/goods/goods",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => 'goods_id',
                        'value' => '',
                        'desc' => 'goods_id请填写砍价商品ID'
                    ]
                ]
            ],
            [
                'sign' => 'lottery',
                'name' => "幸运抽奖",
                'link' => "/lottery/index/index",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'lottery',
                'name' => "幸运抽奖商品详情",
                'link' => "/lottery/goods/goods",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => 'id',
                        'value' => '',
                        'desc' => 'id请填写幸运抽奖ID'
                    ]
                ]
            ],
            [
                'sign' => 'step',
                'name' => "步数宝",
                'link' => "/step/index/index",
                'open_type' => "navigate",
                'params' => []
            ],
            [
                'sign' => 'step',
                'name' => "步数宝商品详情",
                'link' => "/step/goods/goods",
                'open_type' => "navigate",
                'params' => [
                    [
                        'key' => 'goods_id',
                        'value' => '',
                        'desc' => 'goods_id请填写步数宝商品ID'
                    ]
                ]
            ],
        ];
        $pageList = DiyPage::findAll(['store_id' => \Yii::$app->store->id, 'is_delete' => 0, 'status' => 1]);
        foreach ($pageList as $value) {
            $item = [
                'name' => '自定义页面-'.$value->title,
                'link' => '/pages/index/index?page_id=' . $value->id,
                'open_type' => 'navigate',
                'params' => []
            ];
            $list[] = $item;
        }
        return $list;
    }

    /**
     * 底部导航可选的链接
     * @return array
     */
    public function navLink()
    {
        $list = [
            [
                'name' => '首页',
                'url' => '/pages/index/index',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => '分类',
                'url' => '/pages/cat/cat',
                'open_type' => "redirect",
                'params' => [
                    [
                        'key' => "cat_id",
                        'value' => "",
                        'desc' => "cat_id请填写在商品分类中相关分类的ID"
                    ]
                ]
            ],
            [
                'name' => '购物车',
                'url' => '/pages/cart/cart',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => '会员中心',
                'url' => '/pages/member/member',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => '用户中心',
                'url' => '/pages/user/user',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => '商品列表',
                'url' => '/pages/list/list',
                'open_type' => "redirect",
                'params' => [
                    [
                        'key' => "cat_id",
                        'value' => "",
                        'desc' => "cat_id请填写在商品分类中相关分类的ID"
                    ]
                ]
            ],
            [
                'name' => '搜索',
                'url' => '/pages/search/search',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'topic',
                'name' => '专题分类',
                'url' => '/pages/topic-list/topic-list',
                'open_type' => "redirect",
                'params' => [
                    [
                        'key' => "type",
                        'value' => "",
                        'desc' => "type请填写在专题分类中的ID 为空则为全部"
                    ]
                ]
            ],
            [
                'sign' => 'video',
                'name' => '视频专区',
                'url' => '/pages/video/video-list',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'miaosha',
                'name' => '秒杀',
                'url' => '/pages/miaosha/miaosha',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'miaosha',
                'name' => '我的秒杀',
                'url' => '/pages/miaosha/order/order',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => '附近门店',
                'url' => '/pages/shop/shop',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'pintuan',
                'name' => '拼团',
                'url' => '/pages/pt/index/index',
                'open_type' => "redirect",
                'params' => [
                    [
                        'key' => "cid",
                        'value' => "",
                        'desc' => "cid请填写拼团商品列表的分类ID，为空则跳转地址为 拼团"
                    ]
                ],
            ],
            [
                'sign' => 'pintuan',
                'name' => "我的拼团",
                'url' => "/pages/pt/order/order",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'book',
                'name' => '预约',
                'url' => '/pages/book/index/index',
                'open_type' => "redirect",
                'params' => [
                    [
                        'key' => "cid",
                        'value' => "",
                        'desc' => "cid请填写预约商品列表的分类ID,为空则跳转地址为 预约"
                    ]
                ],
            ],
            [
                'sign' => 'book',
                'name' => '我的预约',
                'url' => '/pages/book/order/order',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => "关于我们",
                'url' => "/pages/article-detail/article-detail",
                'open_type' => "redirect",
                'params' => [
                    [
                        'key' => "id",
                        'value' => "about_us",
                        'disabled' => 'disabled',
                        'desc' => "id 值为 about_us, 不能改变"
                    ],
                ]
            ],
            [
                'name' => "服务中心",
                'url' => "/pages/article-list/article-list",
                'open_type' => "redirect",
                'params' => [
                    [
                        'key' => "id",
                        'value' => "2",
                        'disabled' => 'disabled',
                        'desc' => "id 值为 2, 不能改变"
                    ],
                ]
            ],
            [
                'sign' => 'share',
                'name' => '分销中心',
                'url' => '/pages/share/index',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => '快速购买',
                'url' => '/pages/quick-purchase/index/index',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => '一键拨号',
                'url' => 'tel',
                'open_type' => 'tel',
                'params' => [
                    [
                        'key' => "tel",
                        'value' => "",
                        'desc' => "请填写联系电话"
                    ]
                ],
            ],
            [
                'name' => '小程序',
                'url' => 'wxapp',
                'open_type' => 'wxapp',
                'params' => [
                    [
                        'key' => "appid",
                        'value' => "",
                        'desc' => "请填写小程序appid"
                    ],
                    [
                        'key' => "path",
                        'value' => "",
                        'desc' => "打开的页面路径，如pages/index/index，开头请勿加“/”"
                    ],
                ],
            ],
            [
                'name' => '客服',
                'url' => 'contact',
                'open_type' => 'contact',
                'params' => []
            ],
            [
                'name' => '外链',
                'url' => 'web',
                'open_type' => 'web',
                'params' => [
                    [
                        'key' => "web",
                        'value' => "",
                        'desc' => "打开的网页链接（注：域名必须已在微信官方小程序平台设置业务域名）"
                    ]
                ],
            ],
            [
                'sign' => 'integralmall',
                'name' => '积分商城',
                'url' => '/pages/integral-mall/index/index',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'integralmall',
                'name' => '签到',
                'url' => '/pages/integral-mall/register/index',
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'mch',
                'name' => "好店推荐",
                'url' => "/mch/shop-list/shop-list",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'pond',
                'name' => "九宫格抽奖",
                'url' => "/pond/pond/pond",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'scratch',
                'name' => "刮刮卡",
                'url' => "/scratch/index/index",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'step',
                'name' => "步数宝",
                'url' => "/step/index/index",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'sign' => 'mch',
                'name' => "多商户店铺",
                'url' => "/mch/shop/shop",
                'open_type' => "redirect",
                'params' => [
                    [
                        'key' => "mch_id",
                        'value' => "",
                        'desc' => "mch_id 请填写入驻商户ID",
                    ]
                ]
            ],
            [
                'name' => "所有订单",
                'url' => "/pages/order/order?status=-1",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => "待付款订单",
                'url' => "/pages/order/order?status=0",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => "待发货订单",
                'url' => "/pages/order/order?status=1",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => "待收货订单",
                'url' => "/pages/order/order?status=2",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => "已完成订单",
                'url' => "/pages/order/order?status=3",
                'open_type' => "redirect",
                'params' => []
            ],
            [
                'name' => "售后订单",
                'url' => "/pages/order/order?status=4",
                'open_type' => "redirect",
                'params' => []
            ]
        ];
        $pageList = DiyPage::findAll(['store_id' => \Yii::$app->store->id, 'is_delete' => 0, 'status' => 1]);
        foreach ($pageList as $value) {
            $item = [
                'name' =>  '自定义页面-'.$value->title,
                'url' => '/pages/index/index?page_id=' . $value->id,
                'params' => []
            ];
            $list[] = $item;
        }
        return $list;
    }
}
