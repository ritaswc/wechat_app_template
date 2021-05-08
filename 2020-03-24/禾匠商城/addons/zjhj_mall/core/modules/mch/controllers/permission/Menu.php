<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\controllers\permission;


class Menu
{

    public static function getMenu()
    {
        return [
            [
                'name' => '系统管理',
                'is_menu' => true,
                'route' => '',
                'icon' => 'icon-setup',
                'children' => [
                    [
                        'name' => '商城设置',
                        'is_menu' => true,
                        'route' => 'mch/store/setting',
                    ],
                    [
                        'name' => '短信通知',
                        'is_menu' => true,
                        'route' => 'mch/store/sms',
                    ],
                    [
                        'name' => '邮件通知',
                        'is_menu' => true,
                        'route' => 'mch/store/mail',
                    ],
                    [
                        'name' => '运费规则',
                        'is_menu' => true,
                        'route' => 'mch/store/postage-rules',
                        'sub' => [
                            [
                                'name' => '运费规则(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/postage-rules-edit'
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '运费规则状态(U)',
                                'route' => 'mch/store/postage-rules-set-enable',
                            ],
                            [
                                'name' => '运费规则删除',
                                'route' => 'mch/store/postage-rules-delete',
                            ],
                        ]
                    ],
                    [
                        'name' => '包邮规则',
                        'is_menu' => true,
                        'route' => 'mch/store/free-delivery-rules',
                        'sub' => [
                            [
                                'name' => '包邮规则(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/free-delivery-rules-edit'
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '包邮规则删除',
                                'route' => 'mch/store/free-delivery-rules-delete',
                            ],
                        ]
                    ],
                    [
                        'name' => '电子面单',
                        'is_menu' => true,
                        'route' => 'mch/store/express',
                        'sub' => [
                            [
                                'name' => '快递单打印(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/express-edit',
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '快递打印单删除',
                                'route' => 'mch/store/express-del',
                            ],
                        ]
                    ],
                    [
                        'name' => '小票打印',
                        'is_menu' => true,
                        'route' => 'mch/printer/list',
                        'sub' => [
                            [
                                'name' => '小票打印设置',
                                'is_menu' => false,
                                'route' => 'mch/printer/setting',
                            ],
                            [
                                'name' => '小票打印编辑',
                                'is_menu' => false,
                                'route' => 'mch/printer/edit',
                            ]
                        ],
                    ],
                    [
                        'name' => '区域限制购买',
                        'is_menu' => true,
                        'route' => 'mch/store/territorial-limitation',
                        'sub' => [
                            [
                                'name' => '区域限制购买(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/territorial-limitation'
                            ],
                        ],
                    ],
                    [
                        'name' => '起送规则',
                        'is_menu' => true,
                        'route' => 'mch/store/offer-price-edit',
                        'sub' => [
                            [
                                'name' => '起送规则(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/offer-price-edit',
                            ],
                        ],
                    ],
                    [
                        'name' => '退货地址',
                        'is_menu' => true,
                        'route' => 'mch/refund-address/index',
                        'sub' => [
                            [
                                'name' => '退货地址编辑',
                                'is_menu' => false,
                                'route' => 'mch/refund-address/edit',
                            ]
                        ],
                    ],
                ],
            ],
            [
                'name' => '小程序管理',
                'is_menu' => true,
                'route' => '',
                'icon' => 'icon-xiaochengxu3',
                'children' => [
                    [
                        'name' => '微信小程序',
                        'is_menu' => true,
                        'route' => '',
                        'children' => [
                            [
                                'name' => '基础配置',
                                'is_menu' => true,
                                'route' => 'mch/wechat/mp-config',
                            ],
                            [

                                'name' => '模板消息',
                                'is_menu' => true,
                                'route' => 'mch/wechat/template-msg',
                            ],
                            [
                                'name' => '群发模板消息',
                                'is_menu' => true,
                                'route' => 'mch/wechat-platform/send-msg',
                            ],
                            [
                                'name' => '公众号配置',
                                'is_menu' => true,
                                'route' => 'mch/wechat-platform/setting',
                            ],
                            [
                                'offline' => true,
                                'name' => '小程序发布',
                                'is_menu' => true,
                                'route' => 'mch/store/wxapp',
                            ],
                            [
                                'name' => '单商户小程序',
                                'is_menu' => true,
                                'route' => 'mch/store/wxapp-nomch',
                            ],
                            [
                                'key' => 'dianqilai',
                                'name' => '客服设置',
                                'is_menu' => true,
                                'route' => 'mch/contact/index',
                            ],
                        ],
                    ],
                    [
                        'key' => 'alipay',
                        'name' => '支付宝小程序',
                        'is_menu' => true,
                        'route' => '',
                        'children' => [
                            [
                                'name' => '基础配置',
                                'is_menu' => true,
                                'route' => 'mch/alipay/mp-config',
                            ],
                            [
                                'name' => '模板消息',
                                'is_menu' => true,
                                'route' => 'mch/alipay/template-msg',
                            ],
                            [
                                'name' => '小程序发布',
                                'is_menu' => true,
                                'route' => 'mch/alipay/publish',
                            ],
                        ],
                    ],
                    [
                        'name' => '轮播图',
                        'is_menu' => true,
                        'route' => 'mch/store/slide',
                        'sub' => [
                            [
                                'name' => '轮播图(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/slide-edit',
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '轮播图删除',
                                'route' => 'mch/store/slide-del',
                            ]
                        ]
                    ],
                    [
                        'name' => '导航图标',
                        'is_menu' => true,
                        'route' => 'mch/store/home-nav',
                        'sub' => [
                            [
                                'name' => '导航图标(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/home-nav-edit',
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '导航图标删除',
                                'route' => 'mch/store/home-nav-del'
                            ]
                        ]
                    ],
                    [
                        'name' => '图片魔方',
                        'is_menu' => true,
                        'route' => 'mch/store/home-block',
                        'sub' => [
                            [
                                'name' => '图片魔方(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/home-block-edit',
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '图片魔方删除',
                                'route' => 'mch/store/home-block-delete',
                            ]
                        ]
                    ],
                    [
                        'name' => '导航栏',
                        'is_menu' => true,
                        'route' => 'mch/store/navbar',
                        'sub' => [
                            [
                                'name' => '导航恢复默认设置',
                                'is_mune' => false,
                                'route' => '/mch/store/navbar-reset'
                            ]
                        ]
                    ],
                    [
                        'name' => '首页布局',
                        'is_menu' => true,
                        'route' => 'mch/store/home-page',
                    ],
                    [
                        'name' => '用户中心',
                        'is_menu' => true,
                        'route' => 'mch/store/user-center',
                    ],
                    [
                        'name' => '下单表单',
                        'is_menu' => true,
                        'route' => 'mch/store/form',
                    ],
                    [
                        'name' => '页面管理',
                        'is_menu' => true,
                        'route' => '',
                        'children' => [
                            [
                                'name' => '小程序页面',
                                'is_menu' => true,
                                'route' => 'mch/store/wxapp-pages',
                            ],
                            [
                                'name' => '页面标题设置',
                                'is_menu' => true,
                                'route' => 'mch/store/wx-title',
                            ],
                        ]
                    ],
                    [
                        'key' => 'copyright',
                        'name' => '版权设置',
                        'is_menu' => true,
                        'route' => 'mch/we7/copyright',
                    ]
                ]
            ],
            [
                'key' => 'diy',
                'name' => 'DIY装修',
                'is_menu' => true,
                'icon' => 'icon-xitonggongju',
                'route' => '',
                'children' => [
                    [
                        'name' => '模板管理',
                        'is_menu' => true,
                        'route' => 'mch/diy/diy/index',
                        'sub' => [
                            [
                                'is_menu' => false,
                                'name' => '模板编辑',
                                'route' => 'mch/diy/diy/edit',
                            ]
                        ]
                    ],
                    [
                        'name' => '自定义页面',
                        'is_menu' => true,
                        'route' => 'mch/diy/diy/page',
                        'sub' => [
                            [
                                'is_menu' => false,
                                'name' => '页面编辑',
                                'route' => 'mch/diy/diy/page-edit',
                            ]
                        ]
                    ],
                ]
            ],
            [
                'name' => '商品管理',
                'is_menu' => true,
                'route' => 'mch/goods/goods',
                'icon' => 'icon-service',
                'children' => [
                    [
                        'name' => '商品管理',
                        'is_menu' => true,
                        'route' => 'mch/goods/goods',
                        'sub' => [
                            [
                                'name' => '商品(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/goods/goods-edit',
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '商品删除',
                                'route' => 'mch/goods/goods-del'
                            ],
                            [
                                'name' => '商品批量操作删除',
                                'route' => 'mch/goods/batch'
                            ],
                            [
                                'name' => '商品(上架|下架)',
                                'route' => 'mch/goods/goods-up-down'
                            ],
                        ]
                    ],
                    [
                        'name' => '分类',
                        'is_menu' => true,
                        'route' => 'mch/store/cat',
                        'sub' => [
                            [
                                'name' => '商品分类(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/cat-edit',
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '商品分类删除',
                                'route' => 'mch/store/cat-del'
                            ]
                        ]
                    ],
                    [
                        'name' => '淘宝CSV上传',
                        'is_menu' => true,
                        'route' => 'mch/goods/taobao-copy',
                    ],
                ],
            ],
            [
                'name' => '订单管理',
                'is_menu' => true,
                'route' => 'mch/order/index',
                'icon' => 'icon-activity',
                'children' => [
                    [
                        'name' => '订单列表',
                        'is_menu' => true,
                        'route' => 'mch/order/index',
                        'sub' => [
                            [
                                'name' => '订单详情',
                                'is_menu' => false,
                                'route' => 'mch/order/detail'
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '订单移入回收站',
                                'route' => 'mch/order/edit'
                            ],
                            [
                                'name' => '订单添加备注',
                                'route' => 'mch/order/seller-comments'
                            ],
                            [
                                'name' => '订单发货',
                                'route' => 'mch/order/send'
                            ],
                            [
                                'name' => '订单打印',
                                'route' => 'mch/order/print'
                            ],
                            [
                                'name' => '订单申请状态',
                                'route' => 'mch/order/apply-delete-status'
                            ],
                            [
                                'name' => '订单货到付款状态',
                                'route' => 'mch/order/confirm'
                            ],
                        ]
                    ],
                    [
                        'name' => '自提订单',
                        'is_menu' => true,
                        'route' => 'mch/order/offline',
                    ],
                    [
                        'name' => '售后订单',
                        'is_menu' => true,
                        'route' => 'mch/order/refund',
                    ],
                    [
                        'name' => '评价管理',
                        'is_menu' => true,
                        'route' => 'mch/comment/index',
                        'sub' => [
                            [
                                'name' => '订单评价回复',
                                'is_menu' => false,
                                'route' => 'mch/comment/reply',
                            ],
                            [
                                'name' => '订单评价(S|U)',
                                'is_menu' => true,
                                'route' => 'mch/comment/edit',
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '订单评价删除',
                                'route' => 'mch/comment/delete-status'
                            ],
                            [
                                'name' => '订单评价隐藏',
                                'route' => 'mch/comment/hide-status'
                            ],
                        ]
                    ],
                    [
                        'name' => '批量发货',
                        'is_menu' => true,
                        'route' => 'mch/order/batch-ship',
                        'sub' => [
                            [
                                'name' => '模版下载',
                                'is_menu' => true,
                                'route' => 'mch/order/ship-model',
                            ]
                        ]
                    ],
                    [
                        'name' => '消息提醒',
                        'is_menu' => true,
                        'route' => 'mch/store/order-message',
                    ],
                ],
            ],
            [
                'name' => '用户管理',
                'is_menu' => true,
                'route' => 'mch/user/index',
                'icon' => 'icon-people',
                'children' => [
                    [
                        'name' => '用户列表',
                        'is_menu' => true,
                        'route' => 'mch/user/index',
                        'sub' => [
                            [
                                'name' => '用户列表Card',
                                'is_menu' => false,
                                'route' => 'mch/user/card',
                            ],
                            [
                                'name' => '用户卡券',
                                'is_menu' => false,
                                'route' => 'mch/user/coupon',
                            ],
                            [
                                'name' => '用户编辑',
                                'is_menu' => false,
                                'route' => 'mch/user/edit',
                            ],
                            [
                                'name' => '用户余额',
                                'is_menu' => false,
                                'route' => 'mch/user/recharge-money-log'
                            ],
                            [
                                'name' => '积分充值记录',
                                'is_menu' => false,
                                'route' => 'mch/user/rechange-log',
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '用户(设置/取消核销员)',
                                'route' => 'mch/user/clerk-edit'
                            ],
                            [
                                'name' => '用户核销员列表',
                                'route' => 'mch/user/clerk'
                            ],
                            [
                                'name' => '用户列表',
                                'route' => 'mch/user/get-user'
                            ],
                            [
                                'name' => '用户删除',
                                'route' => 'mch/user/del'
                            ],
                            [
                                'name' => '用户积分充值',
                                'route' => 'mch/user/rechange'
                            ],
                            [
                                'name' => '用户金额充值',
                                'route' => 'mch/user/recharge-money'
                            ],
                            [
                                'name' => '用户卡券删除',
                                'route' => 'mch/user/coupon-del'
                            ],
                        ]
                    ],
                    [
                        'name' => '核销员',
                        'is_menu' => true,
                        'route' => 'mch/user/clerk',
                    ],
                    [
                        'name' => '会员等级',
                        'is_menu' => true,
                        'route' => 'mch/user/level',
                        'sub' => [
                            [
                                'name' => '用户会员等级(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/user/level-edit',
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '会员等级状态(启用|禁用)',
                                'route' => 'mch/user/level-type'
                            ],
                            [
                                'name' => '会员等级删除',
                                'route' => 'mch/user/level-del'
                            ],
                        ]
                    ],
                    [
                        'name' => '余额充值记录',
                        'is_menu' => true,
                        'route' => 'mch/user/recharge',
                    ],
                    [
                        'name' => '会员购买记录',
                        'is_menu' => true,
                        'route' => 'mch/user/buy',
                    ],
                    [
                        'name' => '积分充值记录',
                        'is_menu' => true,
                        'route' => 'mch/user/integral-rechange-list',
                    ],
                ],
            ],
            [
                'key' => 'share',
                'name' => '分销中心',
                'is_menu' => true,
                'route' => 'mch/share/index',
                'icon' => 'icon-jiegou',
                'children' => [
                    [
                        'name' => '分销商',
                        'is_menu' => true,
                        'route' => 'mch/share/index',
                        'action' => [
                            [
                                'name' => '分销商添加备注',
                                'route' => 'mch/share/seller-comments'
                            ],
                            [
                                'name' => '分销商佣金设置',
                                'route' => 'mch/share/setting'
                            ],
                            [
                                'name' => '分销商批量设置',
                                'route' => 'mch/share/batch'
                            ],
                            [
                                'name' => '分销商基础设置',
                                'route' => 'mch/share/basic'
                            ],
                            [
                                'name' => '分销商申请审核',
                                'route' => 'mch/share/status'
                            ],
                            [
                                'name' => '分销商确认打款',
                                'route' => 'mch/share/confirm'
                            ],
                            [
                                'name' => '设置推广海报',
                                'route' => 'mch/share/qrcode'
                            ],
                            [
                                'name' => '分销商删除',
                                'route' => 'mch/share/del'
                            ],
                            [
                                'name' => '分销商自定义设置',
                                'route' => 'mch/share/custom'
                            ],
                        ]
                    ],
                    [
                        'name' => '分销订单',
                        'is_menu' => true,
                        'route' => 'mch/share/order',
                    ],
                    [
                        'name' => '分销提现',
                        'is_menu' => true,
                        'route' => 'mch/share/cash',
                    ],
                    [
                        'name' => '分销设置',
                        'is_menu' => true,
                        'route' => 'mch/share/basic',
                        'children' => [
                            [
                                'name' => '基础设置',
                                'is_menu' => true,
                                'route' => 'mch/share/basic',
                                'sub' => [
                                    [
                                        'name' => '分享二维码',
                                        'is_menu' => false,
                                        'route' => 'mch/share/qrcode'
                                    ],
                                ]
                            ],
                            [
                                'name' => '佣金设置',
                                'is_menu' => true,
                                'route' => 'mch/share/setting'
                            ],
                            [
                                'name' => '自定义设置',
                                'is_menu' => true,
                                'route' => 'mch/share/custom'
                            ],
                        ]
                    ],
                ],
            ],
            [
                'name' => '内容管理',
                'is_menu' => true,
                'route' => 'mch/article/index',
                'icon' => 'icon-barrage',
                'children' => [
                    [
                        'name' => '文章',
                        'is_menu' => true,
                        'route' => 'mch/article/index',
                        'sub' => [
                            [
                                'name' => '文章(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/article/edit',
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '文章删除',
                                'route' => 'mch/article/delete',
                            ]
                        ]
                    ],
                    [
                        'key' => 'topic',
                        'name' => '专题分类',
                        'is_menu' => true,
                        'route' => 'mch/topic-type/index',
                        'sub' => [
                            [
                                'name' => '专题分类(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/topic-type/edit',
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '专题分类删除',
                                'route' => 'mch/topic-type/delete'
                            ],
                        ],
                    ],
                    [
                        'key' => 'topic',
                        'name' => '专题',
                        'is_menu' => true,
                        'route' => 'mch/topic/index',
                        'sub' => [
                            [
                                'name' => '专题(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/topic/edit',
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '专题删除',
                                'route' => 'mch/topic/delete'
                            ],
                        ],
                    ],
                    [
                        'key' => 'video',
                        'name' => '视频',
                        'is_menu' => true,
                        'route' => 'mch/store/video',
                        'sub' => [
                            [
                                'name' => '视频(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/video-edit',
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '视频删除',
                                'route' => 'mch/store/video-del',
                            ]
                        ]
                    ],
                    [
                        'name' => '门店',
                        'is_menu' => true,
                        'route' => 'mch/store/shop',
                        'sub' => [
                            [
                                'name' => '门店(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/shop-edit',
                            ]
                        ],
                        'action' => [
                            [
                                'name' => '门店删除',
                                'route' => 'mch/store/shop-del',
                            ],
                            [
                                'name' => '设置默认门店',
                                'route' => 'mch/store/shop-up-down',
                            ],
                            [
                                'name' => '门店导航设置',
                                'route' => 'mch/store/navbar',
                            ],
                            [
                                'name' => '门店导航设置恢复默认',
                                'route' => 'mch/store/navbar-reset',
                            ],
                            [
                                'name' => '邮件设置',
                                'route' => 'mch/store/mail',
                            ],
                        ]
                    ],
                    [
                        'name' => '商品服务',
                        'is_menu' => true,
                        'route' => 'mch/store/service',
                        'sub' => [
                            [
                                'name' => '商品服务(S|U)',
                                'is_menu' => false,
                                'route' => 'mch/store/service-edit',
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '服务删除',
                                'route' => 'mch/store/service-del',
                            ]
                        ]
                    ],
                ],
            ],
            [
                'name' => '营销管理',
                'is_menu' => true,
                'route' => 'mch/coupon/index',
                'icon' => 'icon-coupons',
                'children' => [
                    [
                        'key' => 'coupon',
                        'name' => '优惠券',
                        'is_menu' => true,
                        'route' => 'mch/coupon/index',
                        'sub' => [
                            [
                                'name' => '优惠券改善',
                                'is_menu' => false,
                                'route' => 'mch/coupon/send',
                            ],
                            [
                                'name' => '优惠券编辑',
                                'is_menu' => false,
                                'route' => 'mch/coupon/edit',
                            ]
                        ],
                        'children' => [
                            [
                                'name' => '优惠券管理',
                                'is_menu' => true,
                                'route' => 'mch/coupon/index',
                                'action' => [
                                    [
                                        'name' => '优惠券分类删除',
                                        'route' => 'mch/coupon/delete-cat'
                                    ],
                                    [
                                        'name' => '优惠券删除',
                                        'route' => 'mch/coupon/delete'
                                    ],
                                    [
                                        'name' => '优惠券发放',
                                        'route' => 'mch/coupon/send'
                                    ],
                                ]
                            ],
                            [
                                'name' => '自动发放设置',
                                'is_menu' => true,
                                'route' => 'mch/coupon/auto-send',
                                'sub' => [
                                    [
                                        'name' => '自动发放编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/coupon/auto-send-edit'
                                    ]
                                ],
                                'action' => [
                                    [
                                        [
                                            'name' => '优惠券自动发放设置',
                                            'route' => 'mch/coupon/auto-send-edit'
                                        ],
                                        [
                                            'name' => '优惠券自动发放方案删除',
                                            'route' => 'mch/coupon/auto-send-delete'
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => '卡券',
                        'is_menu' => true,
                        'route' => 'mch/card/index',
                        'sub' => [
                            [
                                'name' => '卡券编辑',
                                'is_menu' => false,
                                'route' => 'mch/card/edit',
                            ],
                        ],
                    ],
                    [
                        'name' => '充值',
                        'is_menu' => true,
                        'route' => 'mch/recharge/index',
                        'sub' => [
                            [
                                'name' => '充值编辑',
                                'is_menu' => false,
                                'route' => 'mch/recharge/edit',
                            ],
                            [
                                'name' => '充值设置',
                                'is_menu' => false,
                                'route' => 'mch/recharge/setting',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => '应用专区',
                'is_menu' => true,
                'route' => 'mch/miaosha/index',
                'icon' => 'icon-pintu-m',
                'children' => [
                    [
                        'key' => 'miaosha',
                        'name' => '整点秒杀',
                        'is_menu' => true,
                        'route' => 'mch/miaosha/index',
                        'children' => [
                            [
                                'name' => '开放时间',
                                'is_menu' => true,
                                'route' => 'mch/miaosha/index',
                            ],
                            [
                                'name' => '秒杀设置',
                                'is_menu' => true,
                                'route' => 'mch/miaosha/setting',

                            ],
                            [
                                'name' => '商品管理',
                                'is_menu' => true,
                                'route' => 'mch/miaosha/goods/index',
                                'sub' => [
                                    [
                                        'name' => '秒杀商品(S|U)',
                                        'is_menu' => false,
                                        'route' => 'mch/miaosha/goods/edit',
                                    ],
                                ],
                                'action' => [
                                    [
                                        'name' => '秒杀商品删除',
                                        'route' => 'mch/miaosha/goods/del'
                                    ],
                                    [
                                        'name' => '秒杀商品(上下架)',
                                        'route' => 'mch/miaosha/goods/goods-up-down'
                                    ],
                                    [
                                        'name' => '秒杀商品批量设置',
                                        'route' => 'mch/miaosha/goods/batch'
                                    ],
                                    [
                                        'name' => '秒杀商品批量设置积分',
                                        'route' => 'mch/miaosha/goods/batch-integral'
                                    ],
                                ]
                            ],
                            [

                                'name' => '商品设置',
                                'is_menu' => true,
                                'route' => 'mch/miaosha/goods',
                                'sub' => [
                                    [
                                        'name' => '商品编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/miaosha/goods-edit',
                                    ],
                                    [
                                        'name' => '商品详情',
                                        'is_menu' => false,
                                        'route' => 'mch/miaosha/goods-detail',
                                    ],
                                    [
                                        'name' => '日期',
                                        'is_menu' => false,
                                        'route' => 'mch/miaosha/calendar',
                                    ],
                                    [
                                        'name' => '多规格详情',
                                        'is_menu' => false,
                                        'route' => 'mch/miaosha/goods-detail-edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => '订单列表',
                                'is_menu' => true,
                                'route' => 'mch/miaosha/order/index',
                                'sub' => [
                                    [
                                        'name' => '秒杀商品发货',
                                        'route' => 'mch/miaosha/order/send'
                                    ],
                                    [
                                        'name' => '秒杀商品详情',
                                        'route' => 'mch/miaosha/order/detail'
                                    ]
                                ]
                            ],
                            [
                                'name' => '自提订单',
                                'is_menu' => true,
                                'route' => 'mch/miaosha/order/offline',
                            ],
                            [
                                'name' => '批量发货',
                                'is_menu' => true,
                                'route' => 'mch/miaosha/order/batch-ship',
                            ],
                            [
                                'name' => '售后订单',
                                'is_menu' => true,
                                'route' => 'mch/miaosha/order/refund',
                                'action' => [
                                    [
                                        'name' => '秒杀订单取消申请处理',
                                        'route' => 'mch/miaosha/order/apply-delete-status'
                                    ]
                                ]
                            ],
                            [
                                'name' => '评价管理',
                                'is_menu' => true,
                                'route' => 'mch/miaosha/comment/index',
                                'action' => [
                                    [
                                        'name' => '整点秒杀隐藏评论',
                                        'route' => 'mch/miaosha/comment/hide-status',
                                    ],
                                    [
                                        'name' => '整点秒杀评论删除',
                                        'route' => 'mch/miaosha/comment/delete-status',
                                    ],
                                ]
                            ],
                        ],
                    ],
                    [
                        'key' => 'pintuan',
                        'name' => '拼团管理',
                        'is_menu' => true,
                        'route' => 'mch/group/goods/index',
                        'children' => [
                            [
                                'name' => '拼团设置',
                                'is_menu' => true,
                                'route' => 'mch/group/setting/index',
                            ],
                            [
                                'name' => '商品管理',
                                'is_menu' => true,
                                'route' => 'mch/group/goods/index',
                                'sub' => [
                                    [
                                        'name' => '商品编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/group/goods/goods-edit',
                                    ],
                                    [
                                        'name' => '商品规格',
                                        'is_menu' => false,
                                        'route' => 'mch/group/goods/goods-attr',
                                    ],
                                    [
                                        'name' => '商品标准',
                                        'is_menu' => false,
                                        'route' => 'mch/group/goods/standard',
                                    ],
                                    [
                                        'name' => '商品标准编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/group/goods/standard-edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => '商品分类',
                                'is_menu' => true,
                                'route' => 'mch/group/goods/cat',
                                'sub' => [
                                    [
                                        'name' => '商品分类编辑',
                                        'is_menu' => true,
                                        'route' => 'mch/group/goods/cat-edit'
                                    ],
                                ]
                            ],
                            [
                                'name' => '订单管理',
                                'is_menu' => true,
                                'route' => 'mch/group/order/index',
                                'sub' => [
                                    [
                                        'name' => '拼团商品详情',
                                        'route' => 'mch/group/order/detail'
                                    ]
                                ]
                            ],
                            [
                                'name' => '售后订单',
                                'is_menu' => true,
                                'route' => 'mch/group/order/refund',
                            ],
                            [
                                'name' => '批量发货',
                                'is_menu' => true,
                                'route' => 'mch/group/order/batch-ship',
                            ],
                            [
                                'name' => '拼团管理',
                                'is_menu' => true,
                                'route' => 'mch/group/order/group',
                                'sub' => [
                                    [
                                        'name' => '拼团列表',
                                        'is_menu' => false,
                                        'route' => 'mch/group/order/group-list'
                                    ],
                                ],
                            ],
                            // 隐藏该菜单
//                            [
//                                'name' => '机器人管理',
//                                'is_menu' => true,
//                                'route' => 'mch/group/robot/index',
//                                'sub' => [
//                                    [
//                                        'name' => '机器人编辑',
//                                        'is_menu' => false,
//                                        'route' => 'mch/group/robot/edit'
//                                    ],
//                                ]
//                            ],
                            [
                                'name' => '轮播图',
                                'is_menu' => true,
                                'route' => 'mch/group/pt/banner',
                                'sub' => [
                                    [
                                        'name' => '轮播图编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/group/pt/slide-edit'
                                    ],
                                ]
                            ],
                            [
                                'name' => '拼团规则',
                                'is_menu' => true,
                                'route' => 'mch/group/article/edit',
                            ],
                            [
                                'name' => '评论管理',
                                'is_menu' => true,
                                'route' => 'mch/group/comment/index',
                                'sub' => [
                                    [
                                        'name' => '客户评价编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/group/comment/edit'
                                    ],
                                ]
                            ],
                            [
                                'name' => '广告设置',
                                'is_menu' => true,
                                'route' => 'mch/group/ad/setting',
                            ],
                            [
                                'name' => '数据统计',
                                'is_menu' => true,
                                'route' => 'mch/group/data/goods',
                                'sub' => [
                                    [
                                        'name' => '用户列表',
                                        'is_menu' => false,
                                        'route' => 'mch/group/data/user'
                                    ],
                                ]
                            ],
                        ],
                    ],
                    [
                        'key' => 'book',
                        'name' => '预约',
                        'is_menu' => true,
                        'route' => 'mch/book/goods/index',
                        'children' => [
                            [
                                'name' => '商品管理',
                                'is_menu' => true,
                                'route' => 'mch/book/goods/index',
                                'sub' => [
                                    [
                                        'name' => '商品编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/book/goods/goods-edit'
                                    ],
                                ]
                            ],
                            [
                                'name' => '商品分类',
                                'is_menu' => true,
                                'route' => 'mch/book/goods/cat',
                                'sub' => [
                                    [
                                        'name' => '商品分类编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/book/goods/cat-edit'
                                    ]
                                ]
                            ],
                            [
                                'name' => '订单管理',
                                'is_menu' => true,
                                'route' => 'mch/book/order/index',
                                'sub' => [
                                    [
                                        'name' => '预约商品详情',
                                        'route' => 'mch/book/order/detail'
                                    ]
                                ]
                            ],
                            [
                                'name' => '基础设置',
                                'is_menu' => true,
                                'route' => 'mch/book/index/setting',
                            ],
                            [
                                'name' => '评论管理',
                                'is_menu' => true,
                                'route' => 'mch/book/comment/index',
                            ],
                        ],
                    ],
                    [
                        'key' => 'fxhb',
                        'name' => '裂变拆红包',
                        'is_menu' => true,
                        'route' => 'mch/fxhb/index/setting',
                        'children' => [
                            [
                                'name' => '红包设置',
                                'is_menu' => true,
                                'route' => 'mch/fxhb/index/setting',
                            ],
                            [
                                'name' => '红包记录',
                                'is_menu' => true,
                                'route' => 'mch/fxhb/index/list',
                            ],
                        ],
                    ],
                    [
                        'key' => 'mch',
                        'name' => '商户管理',
                        'is_menu' => true,
                        'route' => 'mch/mch/index/index',
                        'icon' => 'icon-shanghu',
                        'children' => [
                            [
                                'name' => '商户列表',
                                'is_menu' => true,
                                'route' => 'mch/mch/index/index',
                                'sub' => [
                                    [
                                        'name' => '商户列表编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/mch/index/edit',
                                    ],
                                    [
                                        'name' => '商户列表添加',
                                        'is_menu' => false,
                                        'route' => 'mch/mch/index/add',
                                    ],
                                    [
                                        'name' => '商户设置',
                                        'is_menu' => false,
                                        'route' => 'mch/mch/index/mch-setting',
                                    ],
                                ],
                            ],
                            [
                                'name' => '入驻审核',
                                'is_menu' => true,
                                'route' => 'mch/mch/index/apply',
                            ],
                            [
                                'name' => '所售类目',
                                'is_menu' => true,
                                'route' => 'mch/mch/index/common-cat',
                                'sub' => [
                                    [
                                        'name' => '类目编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/mch/index/common-cat-edit',
                                    ]
                                ],
                            ],
                            [
                                'name' => '提现管理',
                                'is_menu' => true,
                                'route' => 'mch/mch/index/cash',
                            ],
                            [
                                'name' => '商品列表',
                                'is_menu' => true,
                                'route' => 'mch/mch/goods/goods',
                                'sub' => [
                                    [
                                        'name' => '商品详情',
                                        'is_menu' => false,
                                        'route' => 'mch/mch/goods/detail'
                                    ]
                                ]
                            ],
                            [
                                'name' => '订单列表',
                                'is_menu' => true,
                                'route' => 'mch/mch/order/index',
                                'sub' => [
                                    [
                                        'name' => '订单详情',
                                        'is_menu' => false,
                                        'route' => 'mch/mch/order/detail'
                                    ],
                                ]
                            ],
                            [
                                'name' => '多商户设置',
                                'is_menu' => true,
                                'route' => 'mch/mch/index/setting',
                            ],
                            [
                                'name' => '销售报表',
                                'is_menu' => true,
                                'route' => 'mch/mch/index/report-forms',
                            ]
                        ],
                        'sub' => [
                            [
                                'name' => '首页编辑',
                                'is_menu' => false,
                                'route' => 'mch/mch/index/edit',
                            ],
                        ],
                    ],

                    [
                        'key' => 'integralmall',
                        'name' => '积分商城',
                        'is_menu' => true,
                        'icon' => 'icon-manage',
                        'route' => 'mch/integralmall/integralmall/setting',
                        'children' => [
                            [
                                'name' => '积分商城设置',
                                'is_menu' => true,
                                'route' => 'mch/integralmall/integralmall/setting',
                            ],
                            [
                                'name' => '轮播图',
                                'is_menu' => true,
                                'route' => 'mch/integralmall/integralmall/slide',
                                'sub' => [
                                    [
                                        'name' => '轮播图编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/integralmall/integralmall/slide-edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => '商品管理',
                                'is_menu' => true,
                                'route' => 'mch/integralmall/integralmall/goods',
                                'sub' => [
                                    [
                                        'name' => '商品编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/integralmall/integralmall/goods-edit',
                                    ],
                                    [
                                        'name' => '商品列表',
                                        'is_menu' => false,
                                        'mch/integralmall/integralmall/goods-list'
                                    ],
                                ],
                            ],
                            [
                                'name' => '商品分类',
                                'is_menu' => true,
                                'route' => 'mch/integralmall/integralmall/cat',
                                'sub' => [
                                    [
                                        'name' => '商品分类编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/integralmall/integralmall/cat-edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => '优惠券管理',
                                'is_menu' => true,
                                'route' => 'mch/integralmall/integralmall/coupon',
                                'sub' => [
                                    [
                                        'name' => '优惠券管理编辑',
                                        'is_menu' => false,
                                        'route' => 'mch/integralmall/integralmall/coupon-list',
                                    ],
                                ],
                            ],
                            [
                                'name' => '用户兑换劵',
                                'is_menu' => true,
                                'route' => 'mch/integralmall/integralmall/user-coupon',
                            ],
                            [
                                'name' => '订单列表',
                                'is_menu' => true,
                                'route' => 'mch/integralmall/integralmall/order',
                                'sub' => [
                                    [
                                        'name' => '订单详情',
                                        'is_menu' => false,
                                        'route' => 'mch/integralmall/integralmall/detail',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'key' => 'pond',
                        'name' => '九宫格抽奖',
                        'is_menu' => true,
                        'icon' => 'icon-manage',
                        'route' => 'mch/pond/pond/index',
                        'children' => [
                            [
                                'name' => '奖品列表',
                                'is_menu' => true,
                                'route' => 'mch/pond/pond/index',
                                'sub' => [
                                    [
                                        'name' => '商品列表',
                                        'is_menu' => false,
                                        'route' => 'mch/pond/pond/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => '抽奖记录',
                                'is_menu' => true,
                                'route' => 'mch/pond/log/index',
                            ],
                            [
                                'name' => '赠品订单',
                                'is_menu' => true,
                                'route' => 'mch/pond/order/index',
                            ],
                        ],
                    ],
                    [
                        'key' => 'scratch',
                        'name' => '刮刮卡',
                        'is_menu' => true,
                        'icon' => 'icon-manage',
                        'route' => 'mch/scratch/scratch/index',
                        'children' => [
                            [
                                'name' => '基础设置',
                                'is_menu' => true,
                                'route' => 'mch/scratch/scratch/setting',
                            ],
                            [
                                'name' => '奖品列表',
                                'is_menu' => true,
                                'route' => 'mch/scratch/scratch/index',
                                'sub' => [
                                    [
                                        'name' => '新增奖品',
                                        'is_menu' => false,
                                        'route' => 'mch/scratch/scratch/edit',
                                    ],
                                ],
                            ],
                            [
                                'name' => '抽奖记录',
                                'is_menu' => true,
                                'route' => 'mch/scratch/log/index',
                            ],
                            [
                                'name' => '赠品订单',
                                'is_menu' => true,
                                'route' => 'mch/scratch/order/index',
                            ],
                        ],
                    ],
                    [
                        'key' => 'bargain',
                        'name' => '砍价',
                        'is_menu' => true,
                        'icon' => 'icon-quanxianguanli',
                        'route' => '',
                        'children' => [
                            [
                                'is_menu' => true,
                                'name' => '基础设置',
                                'route' => 'mch/bargain/default/setting',
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '编辑设置',
                                        'route' => 'mch/bargain/default/setting-save'
                                    ],
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '轮播图',
                                'route' => 'mch/bargain/default/slide',
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '编辑轮播图',
                                        'route' => 'mch/bargain/default/slide-edit'
                                    ],
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '商品管理',
                                'route' => "mch/bargain/goods/goods",
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '编辑商品',
                                        'route' => "mch/bargain/goods/goods-edit"
                                    ]
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '订单管理',
                                'route' => "mch/bargain/order/index",
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '订单详情',
                                        'route' => 'mch/bargain/order/detail'
                                    ]
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '砍价信息',
                                'route' => "mch/bargain/default/bargain",
                            ],
                        ]
                    ],
                    [
                        'key' => 'lottery',
                        'name' => '抽奖',
                        'is_menu' => true,
                        'icon' => 'icon-manage',
                        'route' => '',
                        'children' => [
                            [
                                'name' => '基础设置',
                                'is_menu' => true,
                                'route' => "mch/lottery/default/setting",
                            ],
                            [
                                'is_menu' => true,
                                'name' => '抽奖列表',
                                'route' => 'mch/lottery/default/goods',
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '抽奖编辑',
                                        'route' => 'mch/lottery/default/goods-edit'
                                    ],
                                    [
                                        'is_menu' => false,
                                        'name' => '抽奖详情',
                                        'route' => 'mch/lottery/default/detail'
                                    ],
                                    [
                                        'is_menu' => false,
                                        'name' => '参与详情',
                                        'route' => 'mch/lottery/default/partake-list'
                                    ],
                                ]
                            ],

                            [
                                'is_menu' => true,
                                'name' => '轮播图',
                                'route' => 'mch/lottery/default/slide',
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '编辑轮播图',
                                        'route' => 'mch/lottery/default/slide-edit'
                                    ],
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '订单管理',
                                'route' => "mch/lottery/order/index",
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '订单详情',
                                        'route' => 'mch/lottery/order/detail'
                                    ]
                                ]
                            ],
                        ],
                    ],
                    [
                        'key' => 'step',
                        'name' => '步数宝',
                        'is_menu' => true,
                        'icon' => 'icon-manage',
                        'route' => '',
                        'children' => [
                            [
                                'name' => '基础设置',
                                'is_menu' => true,
                                'route' => "mch/step/default/setting",
                            ],
                            [
                                'is_menu' => true,
                                'name' => '用户列表',
                                'route' => "mch/step/default/user",
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '兑换记录',
                                        'route' => 'mch/step/default/log'
                                    ]
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '流量主',
                                'route' => "mch/step/default/ad",
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '流量主编辑',
                                        'route' => 'mch/step/default/ad-edit'
                                    ]
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '步数挑战',
                                'route' => "mch/step/default/activity",
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '挑战编辑',
                                        'route' => 'mch/step/default/activity-edit'
                                    ],
                                    [
                                        'is_menu' => false,
                                        'name' => '参与详情',
                                        'route' => 'mch/step/default/partake-list'
                                    ],
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '商品列表',
                                'route' => 'mch/step/goods/goods',
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '商品编辑',
                                        'route' => 'mch/step/goods/goods-edit'
                                    ],
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '轮播图',
                                'route' => 'mch/step/default/slide',
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '编辑轮播图',
                                        'route' => 'mch/step/default/slide-edit'
                                    ],
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '订单管理',
                                'route' => "mch/step/order/index",
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '订单详情',
                                        'route' => 'mch/step/order/detail'
                                    ]
                                ]
                            ],
                        ],
                    ],
                    [
                        'key' => 'gwd',
                        'name' => '好物圈',
                        'is_menu' => true,
                        'icon' => 'icon-manage',
                        'route' => '',
                        'children' => [
                            [
                                'is_menu' => true,
                                'name' => '基础设置',
                                'route' => "mch/gwd/setting/index",
                            ],
                            [
                                'name' => '已购好物圈',
                                'is_menu' => true,
                                'route' => "mch/gwd/buy-list/index",
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '可导入订单列表',
                                        'route' => 'mch/gwd/buy-list/edit'
                                    ]
                                ]
                            ],
                            [
                                'is_menu' => true,
                                'name' => '想买好物圈',
                                'route' => "mch/gwd/like-list/index",
                                'sub' => [
                                    [
                                        'is_menu' => false,
                                        'name' => '想买用户列表',
                                        'route' => 'mch/gwd/like-list/edit'
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => '教程管理',
                'is_menu' => true,
                'icon' => 'icon-iconxuexi',
                'route' => 'mch/handle/index',
                'children' => [
                    [
                        'name' => '操作教程',
                        'is_menu' => true,
                        'route' => 'mch/handle/index',
                    ],
                    [
                        'admin' => true,
                        'name' => '教程设置',
                        'is_menu' => true,
                        'route' => 'mch/handle/setting',
                    ],
                ]
            ],
            [
                'key' => 'permission',
                'name' => '员工管理',
                'is_menu' => true,
                'icon' => 'icon-settings',
                'route' => '',
                'children' => [
                    [
                        'name' => '角色列表',
                        'is_menu' => true,
                        'icon' => 'icon-manage',
                        'route' => 'mch/permission/role/index',
                        'sub' => [
                            [
                                'is_menu' => false,
                                'name' => '添加角色',
                                'route' => 'mch/permission/role/create',
                            ],
                            [
                                'is_menu' => false,
                                'name' => '编辑角色',
                                'route' => 'mch/permission/role/edit',
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '角色(U)',
                                'route' => 'mch/permission/role/update',
                            ],
                            [
                                'name' => '角色删除',
                                'route' => 'mch/permission/role/destroy',
                            ],
                            [
                                'name' => '角色(S)',
                                'route' => 'mch/permission/role/store',
                            ],
                        ]
                    ],
                    [
                        'is_menu' => true,
                        'name' => '员工管理',
                        'route' => 'mch/permission/user/index',
                        'sub' => [
                            [
                                'is_menu' => false,
                                'name' => '添加员工',
                                'route' => 'mch/permission/user/create',
                            ],
                            [
                                'is_menu' => false,
                                'name' => '编辑员工',
                                'route' => 'mch/permission/user/edit',
                            ],
                        ],
                        'action' => [
                            [
                                'name' => '用户(U)',
                                'route' => 'mch/permission/user/update',
                            ],
                            [
                                'name' => '用户删除',
                                'route' => 'mch/permission/user/destroy',
                            ],
                            [
                                'name' => '用户(S)',
                                'route' => 'mch/permission/user/store',
                            ],
                        ]
                    ],
                    [
                        'is_menu' => true,
                        'name' => '操作日志',
                        'route' => 'mch/action-log/index',
                        'sub' => [
                            [
                                'is_menu' => false,
                                'name' => '日志开关',
                                'route' => 'mch/action-log/switch',
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => '系统工具',
                'is_menu' => true,
                'icon' => 'icon-xitonggongju',
                'route' => '',
                'children' => [
                    [
                        'is_ind' => true,
                        'admin' => true,
                        'name' => '超限设置',
                        'is_menu' => true,
                        'route' => 'mch/system/overrun',
                    ],
                    [
                        'is_ind' => true,
                        'admin' => true,
                        'name' => '数据库优化',
                        'is_menu' => true,
                        'route' => 'mch/system/db-optimize',
                    ],
                    [
                        'is_ind' => true,
                        'admin' => true,
                        'name' => '上传设置',
                        'is_menu' => true,
                        'route' => 'mch/store/upload',
                    ],
                    [
                        'admin' => true,
                        'we7' => true,
                        'name' => '权限分配',
                        'is_menu' => true,
                        'route' => 'mch/we7/auth',
                    ],
                    [
                        'is_ind' => true,
                        'admin' => true,
                        'name' => '小程序管理',
                        'is_menu' => true,
                        'route' => 'mch/we7/copyright-list',
                    ],
                    [
                        // TODO 子账号也需要清除缓存权限
                        // 'admin' => true,
                        'is_ind' => true,
                        'name' => '缓存',
                        'is_menu' => true,
                        'route' => 'mch/cache/index',
                    ],
                    [
                        'is_ind' => true,
                        'admin' => true,
                        'offline' => true,
                        'name' => '更新',
                        'is_menu' => true,
                        'route' => 'mch/update/index',
                    ],
//                    [
//                        'name' => '商城导出',
//                        'is_menu' => true,
//                        'route' => 'mch/system/export',
//                    ],
                ],
            ],
            [
                'name' => '数据统计',
                'is_menu' => true,
                'icon' => 'icon-tongji',
                'route' => 'mch/statistic/index'
            ],
        ];
    }
}
