<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/10
 * Time: 10:47
 */

namespace app\modules\api\models;

class StoreFrom extends ApiModel
{

    public function search()
    {
        $wxappUrl = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/wxapp/images';
        $images = [
            'integralMall' => [ // 模块名称
                'register' => [ // 小程序页面
                    'register_bg' => [ // 图片名称
                        'url' => $wxappUrl . '/register.png' // 图片路径
                    ]
                ]
            ],
            'pond' => [
                'pond' => [
                    'pond_head' => [
                        'url' => $wxappUrl . '/pond-head.png'
                    ],
                    'pond_success' => [
                        'url' => $wxappUrl . '/pond-success.png'
                    ],
                    'pond_empty' => [
                        'url' => $wxappUrl . '/pond-empty.png'
                    ]
                ]
            ],
            'bargain' => [
                'bargain_goods' => [
                    'time_bg' => [
                        'url' => $wxappUrl . '/icon-bargain-time.png'
                    ],
                    'flow' => [
                        'url' => $wxappUrl . '/icon-bargain-flow.png'
                    ],
                    'click' => [
                        'url' => $wxappUrl . '/icon-bargain-click.png'
                    ],
                    'help' => [
                        'url' => $wxappUrl . '/icon-bargain-help.png',
                    ],
                    'price' => [
                        'url' => $wxappUrl . '/icon-bargain-price.png',
                    ],
                    'buy' => [
                        'url' => $wxappUrl . '/icon-bargain-buy.png',
                    ],
                    'jiantou' => [
                        'url' => $wxappUrl . '/icon-bargain-jiantou.png',
                    ],
                    'shuoming' => [
                        'url' => $wxappUrl . '/icon-bargain-shuoming.png',
                    ],
                    'goods' => [
                        'url' => $wxappUrl . '/icon-bargain-goods.png',
                    ]
                ],
                'activity' => [
                    'bg' => [
                        'url' => $wxappUrl . '/icon-bargain-bg.png'
                    ],
                    'buy' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-buy.png'
                    ],
                    'continue' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-c.png'
                    ],
                    'progress' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-progress.png'
                    ],
                    'used' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-used.png'
                    ],
                    'down' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-jiantou-down.png'
                    ],
                    'up' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-jiantou-up.png'
                    ],
                    'join' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-join.png'
                    ],
                    'header_bg' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-header.png'
                    ],
                    'help' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-help.png'
                    ],
                    'join_m' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-join-m.png'
                    ],
                    'x' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-x.png'
                    ],
                    'more' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-more.png'
                    ],
                    'buy_b' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-buy-b.png'
                    ],
                    'header_bg_1' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-header-1.png'
                    ],
                    'header_bg_2' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-header-2.png'
                    ],
                    'header_bg_3' => [
                        'url' => $wxappUrl . '/icon-bargain-activity-header-3.gif'
                    ],
                ],
                'list' => [
                    'right' => [
                        'url' => $wxappUrl . '/icon-bargain-list-right.png'
                    ]
                ]
            ],
            'store' => [
                'disabled' => [
                    'url' => $wxappUrl . '/disabled.png'
                ],
                'bg' => [
                    'url' => $wxappUrl . '/bg.png'
                ],
                'car' => [
                    'url' => $wxappUrl . '/car.png'
                ],
                'binding_pic' => [
                    'url' => $wxappUrl . '/binding-pic.png'
                ],
                'gold' => [
                    'url' => $wxappUrl . '/gold.png'
                ],
                'clear' => [
                    'url' => $wxappUrl . '/clear.png'
                ],
                'good_recommend' => [
                    'url' => $wxappUrl . '/good-recommend.png'
                ],
                'guige' => [
                    'url' => $wxappUrl . '/guige.jpg'
                ],
                'home_gwc' => [
                    'url' => $wxappUrl . '/home-gwc.png'
                ],
                'huiyuan_bg' => [
                    'url' => $wxappUrl . '/huiyuan-bg.png'
                ],
                'check' => [
                    'url' => $wxappUrl . '/icon-check.png'
                ],
                'checked' => [
                    'url' => $wxappUrl . '/icon-checked.png'
                ],
                'clerk' => [
                    'url' => $wxappUrl . '/icon-clerk.png'
                ],
                'close' => [
                    'url' => $wxappUrl . '/icon-close.png'
                ],
                'close2' => [
                    'url' => $wxappUrl . '/icon-close2.png'
                ],
                'close3' => [
                    'url' => $wxappUrl . '/icon-close3.png'
                ],
                'close4' => [
                    'url' => $wxappUrl . '/icon-close4.png'
                ],
                'delete' => [
                    'url' => $wxappUrl . '/icon-delete.png'
                ],
                'detail_love' => [
                    'url' => $wxappUrl . '/icon-detail-love.png'
                ],
                'edit' => [
                    'url' => $wxappUrl . '/icon-edit.png'
                ],
                'favorite' => [
                    'url' => $wxappUrl . '/icon-favorite.png'
                ],
                'favorite_active' => [
                    'url' => $wxappUrl . '/icon-favorite-active.png'
                ],
                'good_shop' => [
                    'url' => $wxappUrl . '/icon-good-shop.png'
                ],
                'group_share' => [
                    'url' => $wxappUrl . '/icon-group-share.png'
                ],
                'image_picker' => [
                    'url' => $wxappUrl . '/icon-image-picker.png'
                ],
                'jiantou_r' => [
                    'url' => $wxappUrl . '/icon-jiantou-r.png'
                ],
                'member_rights' => [
                    'url' => $wxappUrl . '/icon-member-rights.png'
                ],
                'my_exchange' => [
                    'url' => $wxappUrl . '/icon-my-exchange.png'
                ],
                'ntegration' => [
                    'url' => $wxappUrl . '/icon-ntegration.png'
                ],
                'pay_right' => [
                    'url' => $wxappUrl . '/icon-pay-right.png'
                ],
                'icon_play' => [
                    'url' => $wxappUrl . '/icon-play.png'
                ],
                'service' => [
                    'url' => $wxappUrl . '/icon-service.png'
                ],
                'shuoming' => [
                    'url' => $wxappUrl . '/icon-shuoming.png'
                ],
                'store' => [
                    'url' => $wxappUrl . '/icon-store.png'
                ],
                'time_bg' => [
                    'url' => $wxappUrl . '/icon-time-bg.png'
                ],
                'time_split' => [
                    'url' => $wxappUrl . '/icon-time-split.png'
                ],
                'uncheck' => [
                    'url' => $wxappUrl . '/icon-uncheck.png'
                ],
                'up' => [
                    'url' => $wxappUrl . '/icon-up.png'
                ],
                'address' => [
                    'url' => $wxappUrl . '/icon-address.png'
                ],
                'order_status_bar' => [
                    'url' => $wxappUrl . '/img-order-status-bar.png'
                ],
                'pc_login' => [
                    'url' => $wxappUrl . '/img-pc-login.png'
                ],
                'jia' => [
                    'url' => $wxappUrl . '/jia.png'
                ],
                'jian' => [
                    'url' => $wxappUrl . '/jian.png'
                ],
                'jiangli' => [
                    'url' => $wxappUrl . '/jiangli.png'
                ],
                'quick_hot' => [
                    'url' => $wxappUrl . '/quick-hot.png'
                ],
                'search_index' => [
                    'url' => $wxappUrl . '/serach-index-icon.png'
                ],
                'shou' => [
                    'url' => $wxappUrl . '/shou.png'
                ],
                'video_play' => [
                    'url' => $wxappUrl . '/video-play.png'
                ],
                'yougoods' => [
                    'url' => $wxappUrl . '/yougoods.jpg'
                ],
                'binding' => [
                    'url' => $wxappUrl . '/binding.png',
                    'remark' => '绑定公众号'
                ],
                'binding_yes' => [
                    'url' => $wxappUrl . '/binding_yes.png',
                    'remark' => '已绑定公众号'
                ],
                'share_commission' => [
                    'url' => $wxappUrl . '/share_commission.png',
                    'remark' => '商品详情分销价'
                ],
                'member_price' => [
                    'url' => $wxappUrl . '/member_price.png',
                    'remark' => '商品详情会员价'
                ]
            ],
            'pt' => [
                'active' => [
                    'url' => $wxappUrl . '/ico-pt-active.png'
                ],
                'text' => [
                    'url' => $wxappUrl . '/icon-pt-text.png'
                ],
                'group_bg' => [
                    'url' => $wxappUrl . '/icon-pt-group-bg.png'
                ],
                'address_bottom' => [
                    'url' => $wxappUrl . '/pt-addres-bottom.png'
                ],
                'address_top' => [
                    'url' => $wxappUrl . '/pt-addres-top.png'
                ],
                'address' => [
                    'url' => $wxappUrl . '/pt-address.png'
                ],
                'details' => [
                    'url' => $wxappUrl . '/pt-details-pt.png'
                ],
                'empty_order' => [
                    'url' => $wxappUrl . '/pt-empty-order.png'
                ],
                'fail' => [
                    'url' => $wxappUrl . '/pt-fail.png'
                ],
                'favorite' => [
                    'url' => $wxappUrl . '/pt-favorite.png'
                ],
                'go_home' => [
                    'url' => $wxappUrl . '/pt-go-home.png'
                ],
                'no_group_num' => [
                    'url' => $wxappUrl . '/pt-no-group-num-pic.png'
                ],
                'search_clear' => [
                    'url' => $wxappUrl . '/pt-search-clear.png'
                ],
                'search' => [
                    'url' => $wxappUrl . '/pt-search-icon.png'
                ],
                'shop_car' => [
                    'url' => $wxappUrl . '/pt-shop-car.png'
                ],
                'success' => [
                    'url' => $wxappUrl . '/pt-success.png'
                ]
            ],
            'balance' => [
                'left' => [
                    'url' => $wxappUrl . '/icon-balance-left.png'
                ],
                'right' => [
                    'url' => $wxappUrl . '/icon-balance-right.png'
                ],
                'recharge' => [
                    'url' => $wxappUrl . '/icon-balance-recharge.png'
                ],
                'recharge_bg' => [
                    'url' => $wxappUrl . '/icon-balance-recharge-bg.png'
                ]
            ],
            'card' => [
                'btn' => [
                    'url' => $wxappUrl . '/icon-card-btn.png'
                ],
                'del' => [
                    'url' => $wxappUrl . '/icon-card-del.png'
                ],
                'qrcode' => [
                    'url' => $wxappUrl . '/icon-card-qrcode.png'
                ],
                'top' => [
                    'url' => $wxappUrl . '/icon-card-top.png'
                ]
            ],
            'coupon' => [
                'coupon' => [
                    'url' => $wxappUrl . '/icon-coupon.png'
                ],
                'disabled' => [
                    'url' => $wxappUrl . '/icon-coupon-disabled.png'
                ],
                'enabled' => [
                    'url' => $wxappUrl . '/icon-coupon-enabled.png'
                ],
                'index' => [
                    'url' => $wxappUrl . '/icon-coupon-index.png'
                ],
                'no' => [
                    'url' => $wxappUrl . '/icon-coupon-no.png'
                ],
                'bg' => [
                    'url' => $wxappUrl . '/img-get-coupon-bg.png'
                ],
                'item_bg' => [
                    'url' => $wxappUrl . '/img-get-coupon-item-bg.png'
                ]
            ],
            'system' => [
                'wechatapp' => [
                    'url' => $wxappUrl . '/icon-wechatapp.png'
                ],
                'yuyue' => [
                    'url' => $wxappUrl . '/icon-yuyue.png'
                ],
                'loading' => [
                    'url' => $wxappUrl . '/loading.svg'
                ],
                'loading2' => [
                    'url' => $wxappUrl . '/loading2.svg'
                ],
                'loading_black' => [
                    'url' => $wxappUrl . '/loading-black.svg'
                ],
                'alipay' => [
                    'url' => $wxappUrl . '/icon-alipay.png'
                ]
            ],
            'integral' => [
                'all' => [
                    'url' => $wxappUrl . '/icon-integral-all.png'
                ],
                'close' => [
                    'url' => $wxappUrl . '/icon-integral-close.png'
                ],
                'detail' => [
                    'url' => $wxappUrl . '/icon-integral-detail.png'
                ],
                'head' => [
                    'url' => $wxappUrl . '/icon-integral-head.png'
                ],
                'shibai' => [
                    'url' => $wxappUrl . '/icon-integral-shibai.png'
                ],
                'success' => [
                    'url' => $wxappUrl . '/icon-integral-success.png'
                ]
            ],
            'miaosha' => [
                'miaosha' => [
                    'url' => $wxappUrl . '/icon-miaosha.png'
                ],
                'ms_activity_bg' => [
                    'url' => $wxappUrl . '/ms_activity_bg.png',
                    'remark' => '秒杀活动到计时背景图'
                ],
            ],
            'notice' => [
                'jiantou' => [
                    'url' => $wxappUrl . '/icon-notice-jiantou.png'
                ],
                'title' => [
                    'url' => $wxappUrl . '/icon-notice-title.png'
                ],
                'notice' => [
                    'url' => $wxappUrl . '/icon-notice.png'
                ],
            ],
            'point' => [
                'gray' => [
                    'url' => $wxappUrl . '/icon-point-gray.png'
                ],
                'green' => [
                    'url' => $wxappUrl . '/icon-point-green.png'
                ]
            ],
            'register' => [
                'register' => [
                    'url' => $wxappUrl . '/icon-register.png'
                ],
                'is_register' => [
                    'url' => $wxappUrl . '/icon-is-register.png'
                ],
                'close' => [
                    'url' => $wxappUrl . '/icon-register-close.png'
                ],
                'head' => [
                    'url' => $wxappUrl . '/icon-register-head.png'
                ],
                'left' => [
                    'url' => $wxappUrl . '/icon-register-left.png'
                ],
                'right' => [
                    'url' => $wxappUrl . '/icon-register-right.png'
                ],
                'quan' => [
                    'url' => $wxappUrl . '/icon-register-quan.png'
                ],
                'sign_in' => [
                    'url' => $wxappUrl . '/icon-register-sign-in.png'
                ],
            ],
            'search' => [
                'search' => [
                    'url' => $wxappUrl . '/icon-search.png'
                ],
                'search_no' => [
                    'url' => $wxappUrl . '/icon-search-no.png'
                ],
                's_up' => [
                    'url' => $wxappUrl . '/search_up.png'
                ]
            ],
            'share' => [
                'share' => [
                    'url' => $wxappUrl . '/icon-share.png'
                ],
                'ant' => [
                    'url' => $wxappUrl . '/icon-share-ant.png'
                ],
                'bank' => [
                    'url' => $wxappUrl . '/icon-share-bank.png'
                ],
                'friend' => [
                    'url' => $wxappUrl . '/icon-share-friend.png'
                ],
                'qrcode' => [
                    'url' => $wxappUrl . '/icon-share-qrcode.png'
                ],
                'selected' => [
                    'url' => $wxappUrl . '/icon-share-selected.png'
                ],
                'tip' => [
                    'url' => $wxappUrl . '/icon-share-tip.png'
                ],
                'wechat' => [
                    'url' => $wxappUrl . '/icon-share-wechat.png'
                ],
                'down' => [
                    'url' => $wxappUrl . '/img-share-down.png'
                ],
                'info' => [
                    'url' => $wxappUrl . '/img-share-info.png'
                ],
                'money' => [
                    'url' => $wxappUrl . '/img-share-money.png'
                ],
                'img_qrcode' => [
                    'url' => $wxappUrl . '/img-share-qrcode.png'
                ],
                'right' => [
                    'url' => $wxappUrl . '/img-share-right.png'
                ],
                'shop' => [
                    'url' => $wxappUrl . '/img-share-shop.png'
                ]
            ],
            'shop' => [
                'dingwei' => [
                    'url' => $wxappUrl . '/icon-shop-dingwei.png'
                ],
                'love' => [
                    'url' => $wxappUrl . '/icon-shop-love.png'
                ],
                'nav' => [
                    'url' => $wxappUrl . '/icon-shop-nav.png'
                ],
                'nav_one' => [
                    'url' => $wxappUrl . '/icon-shop-nav-1.png'
                ],
                'search' => [
                    'url' => $wxappUrl . '/icon-shop-search.png'
                ],
                'tel' => [
                    'url' => $wxappUrl . '/icon-shop-tel.png'
                ],
                'down' => [
                    'url' => $wxappUrl . '/icon-shop-down.png'
                ]
            ],
            'sort' => [
                'down' => [
                    'url' => $wxappUrl . '/icon-sort-down.png'
                ],
                'down_active' => [
                    'url' => $wxappUrl . '/icon-sort-down-active.png'
                ],
                'up' => [
                    'url' => $wxappUrl . '/icon-sort-up.png'
                ],
                'up_active' => [
                    'url' => $wxappUrl . '/icon-sort-up-active.png'
                ],
            ],
            'topic' => [
                'love' => [
                    'url' => $wxappUrl . '/icon-topic-love.png'
                ],
                'love_active' => [
                    'url' => $wxappUrl . '/icon-topic-love-active.png'
                ],
                'share' => [
                    'url' => $wxappUrl . '/icon-topic-share.png'
                ]
            ],
            'user' => [
                'kf' => [
                    'url' => $wxappUrl . '/icon-user-kf.png'
                ],
                'level' => [
                    'url' => $wxappUrl . '/icon-user-level.png'
                ],
                'balance' => [
                    'url' => $wxappUrl . '/icon-user-balance.png'
                ],
                'wallet' => [
                    'url' => $wxappUrl . '/icon-user-wallet.png'
                ],
                'integral' => [
                    'url' => $wxappUrl . '/icon-user-integral.png'
                ],
                'coupon_xia' => [
                    'url' => $wxappUrl . '/user-coupon-xia.png'
                ]
            ],
            'cart' => [
                'add' => [
                    'url' => $wxappUrl . '/cart-add.png'
                ],
                'less' => [
                    'url' => $wxappUrl . '/cart-less.png'
                ],
                'no_add' => [
                    'url' => $wxappUrl . '/cart-no-add.png'
                ],
                'no_less' => [
                    'url' => $wxappUrl . '/cart-no-less.png'
                ],
            ],
            'nav' => [
                'cart' => [
                    'url' => $wxappUrl . '/nav-icon-cart.png'
                ],
                'index' => [
                    'url' => $wxappUrl . '/nav-icon-index.png'
                ]
            ],
            'yy' => [
                'form_title' => [
                    'url' => $wxappUrl . '/yy-form-title.png'
                ]
            ],
            'scratch' => [
                'index' => [
                    'scratch_bg' => [
                         'url' => $wxappUrl . '/scratch_bg.png'
                    ],
                    'scratch_success' => [
                         'url' => $wxappUrl . '/scratch_success.png'
                    ]
                ]
            ],
            'goods' => [
                'goods' => [
                    'address' => [
                        'url' => $wxappUrl . '/icon-goods-address.png'
                    ]
                ]
            ],
            'step' => [
                'dare_bg' => [
                    'url' => $wxappUrl . '/dare-bg.png'
                ],
                'home_bg' => [
                    'url' => $wxappUrl . '/home-bg.png'
                ],
                'join_bg' => [
                    'url' => $wxappUrl . '/join-bg.png'
                ],
                'detail_bg' => [
                    'url' => $wxappUrl . '/detail-bg.png'
                ],
                'log_bg' => [
                    'url' => $wxappUrl . '/log-bg.png'
                ],
            ],
            'lottery' => [
                'time' => [
                    'url' => $wxappUrl . '/lottery_time.png'
                ]
            ],
            'cell' => [
                'cell_1' => [
                    'url' => $wxappUrl . '/icon-cell-1.png'
                ],
                'cell_2' => [
                    'url' => $wxappUrl . '/icon-cell-2.png'
                ],
                'cell_3' => [
                    'url' => $wxappUrl . '/icon-cell-3.png'
                ],
                'cell_4' => [
                    'url' => $wxappUrl . '/icon-cell-4.png'
                ],
                'cell_5' => [
                    'url' => $wxappUrl . '/icon-cell-5.png'
                ],
            ]
        ];

        return $images;
    }
}
