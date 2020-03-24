<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/6/26
 * Time: 14:38
 */

namespace app\modules\mch\models;

use app\models\Option;
use app\models\Store;
use app\models\WechatApp;

class StoreSettingForm extends MchModel
{
    public $store_id;
    public $name;
    public $order_send_tpl;

    public $app_id;
    public $app_secret;
    public $mch_id;
    public $key;
    public $cert_pem;
    public $key_pem;

    public $contact_tel;
    public $show_customer_service;
    public $dial;
    public $dial_pic;
    public $copyright;
    public $copyright_pic_url;
    public $copyright_url;

    public $delivery_time;
    public $after_sale_time;

    public $kdniao_mch_id;
    public $kdniao_api_key;

    public $cat_style;
    public $cut_thread;
    public $purchase_frame;
    public $is_recommend;
    public $recommend_count;

    public $address;
    public $cat_goods_cols;

    public $over_day;
    public $is_offline;
    public $is_coupon;

    public $cat_goods_count;
    public $send_type;
    public $nav_count;
    public $service;
    public $integral;
    public $integration;
    public $notice;
    public $postage;
    public $web_service;
    public $web_service_url;
    public $web_service_status;
    public $payment;
    public $wxapp;
    public $is_comment;
    public $is_sales;
    public $is_share_price;
    public $is_member_price;

    public $quick_navigation;
    public $phone_auth;
    public $good_negotiable;
    public $buy_member;
    public $logo;
    public $quick_map;
    public $is_official_account;
    public $mobile_verify;

    public function rules()
    {
        return [
            [['name', 'app_id', 'app_secret', 'mch_id', 'key', 'order_send_tpl', 'contact_tel', 'copyright', 'copyright_pic_url', 'copyright_url', 'kdniao_mch_id', 'kdniao_api_key', 'address', 'cert_pem', 'key_pem', 'dial_pic', 'web_service', 'web_service_url', 'payment', 'wxapp', 'quick_navigation', 'good_negotiable', 'quick_map'], 'trim'],
            [['name', 'cat_goods_cols', 'integral',], 'required'],
            [['order_send_tpl', 'contact_tel', 'kdniao_mch_id', 'kdniao_api_key', 'address', 'service', 'integration', 'notice', 'web_service', 'web_service_url', 'logo', 'web_service_status'], 'string'],
            [['show_customer_service', 'cat_style', 'cut_thread', 'purchase_frame', 'is_recommend', 'cat_goods_cols', 'is_offline', 'is_coupon', 'cat_goods_count', 'send_type', 'nav_count', 'dial', 'is_comment', 'is_sales', 'phone_auth', 'buy_member', 'mobile_verify', 'web_service_status'], 'integer', 'max' => 99999999],
            ['cat_goods_count', 'default', 'value' => 6],
            [['cat_goods_count', 'recommend_count', 'is_share_price', 'is_member_price', 'is_official_account'], 'integer', 'min' => 0, 'max' => 100],
            [['cert_pem', 'key_pem'], 'default', 'value' => '0'],
            [['postage'], 'number', 'min' => -1],
            [['over_day'], 'number', 'min' => 0],
            [['delivery_time', 'after_sale_time', 'over_day',], 'integer', 'min' => 0,'max' => 200000000],
            [['integral'],'integer','min' => 1, 'max' => 99999999],
            [['name'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'is_delete' => 'Is Delete',
            'is_recycle' => '回收站：0=否，1=是',
            'acid' => '微擎公众号id',
            'user_id' => '用户id',
            'wechat_platform_id' => '微信公众号id',
            'wechat_app_id' => '微信小程序id',
            'name' => '店铺名称',
            'order_send_tpl' => '发货通知模板消息id',
            'contact_tel' => '联系电话',
            'show_customer_service' => '是否显示在线客服：0=否，1=是',
            'copyright' => 'Copyright',
            'copyright_pic_url' => 'Copyright Pic Url',
            'copyright_url' => '版权的超链接',
            'delivery_time' => '收货时间',
            'after_sale_time' => '售后时间',
            'use_wechat_platform_pay' => '是否使用公众号支付：0=否，1=是',
            'kdniao_mch_id' => '快递鸟商户号',
            'kdniao_api_key' => '快递鸟api key',
            'cat_style' => '分类页面样式：1=无侧栏，2=有侧栏',
            'cut_thread' => '分类分割线   0关闭   1开启',
            'home_page_module' => '首页模块布局',
            'address' => '店铺地址',
            'cat_goods_cols' => '首页分类商品列数',
            'over_day' => '未支付订单超时时间',
            'is_offline' => '是否开启自提',
            'is_coupon' => '是否开启优惠券',
            'cat_goods_count' => '首页分类的商品个数',
            'send_type' => '发货方式：0=快递或自提，1=仅快递，2=仅自提',
            'member_content' => '会员等级说明',
            'nav_count' => '首页导航栏个数 0--4个 1--5个',
            'integral' => '一元抵多少积分',
            'integration' => '积分使用说明',
            'dial' => '一键拨号开关  0关闭  1开启',
            'dial_pic' => '拨号图标',
            'purchase_frame' => 'Purchase Frame',
            'is_recommend' => '推荐商品状态 1：开启 0 ：关闭',
            'recommend_count' => '推荐商品数量',
            'is_sales' => '商城商品销量开关',
            'status' => '商城禁用状态 0.未禁用|1.禁用',
            'is_comment' => '商城评价开关：0.关闭 1.开启',
            'quick_navigation' => '首页快捷导航',
            'good_negotiable' => '商品面议方式',
            'buy_member' => '是否购买会员',
            'logo'  => '商城logo',
            'is_official_account' => '关联公众号组件'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $store = Store::findOne($this->store_id);
        $store->name = $this->name;
        $store->order_send_tpl = $this->order_send_tpl;
        $store->contact_tel = $this->contact_tel;
        $store->show_customer_service = $this->show_customer_service;
        $store->dial = $this->dial;
        $store->dial_pic = $this->dial_pic;
        $store->copyright = $this->copyright;
        $store->copyright_pic_url = $this->copyright_pic_url;
        $store->copyright_url = $this->copyright_url;
        $store->delivery_time = $this->delivery_time;
        $store->after_sale_time = $this->after_sale_time;
        $store->kdniao_mch_id = $this->kdniao_mch_id;
        $store->kdniao_api_key = $this->kdniao_api_key;
        $store->cat_style = $this->cat_style;
        $store->cut_thread = $this->cut_thread;
        $store->address = $this->address;
        $store->cat_goods_cols = $this->cat_goods_cols;
        $store->over_day = $this->over_day;
        $store->is_offline = $this->is_offline;
        $store->is_coupon = $this->is_coupon;
        $store->cat_goods_count = $this->cat_goods_count;
        $store->send_type = $this->send_type;
        $store->nav_count = $this->nav_count;
        $store->integral = $this->integral ?: 10;
        $store->integration = $this->integration;
        $store->purchase_frame = $this->purchase_frame;
        $store->is_recommend = $this->is_recommend;
        $store->recommend_count = $this->recommend_count;
        $store->is_comment = $this->is_comment;
        $store->is_sales = $this->is_sales;
        $store->is_member_price = $this->is_member_price;
        $store->is_share_price = $this->is_share_price;
        $store->buy_member = $this->buy_member;
        $store->logo = $this->logo;
        $store->is_official_account = $this->is_official_account;
        $store->save();

//        Option::set('service', $this->service, $this->store_id, 'admin');
//        Option::set('notice', $this->notice, $this->store_id, 'admin');
        if (!$this->payment) {
            $this->payment['wechat'] = 1;
        }
        $payment = \Yii::$app->serializer->encode($this->payment);
        $wxapp = \Yii::$app->serializer->encode($this->wxapp);
        $quick_navigation = \Yii::$app->serializer->encode($this->quick_navigation);
        if (!$this->good_negotiable) {
            $this->good_negotiable['contact'] = 1;
        }
        $good_negotiable = \Yii::$app->serializer->encode($this->good_negotiable);

        $list = [
            [
                'name' => 'service',
                'value' => $this->service
            ],
            [
                'name' => 'notice',
                'value' => $this->notice,
            ],
            [
                'name' => 'postage',
                'value' => $this->postage ? $this->postage : '',
            ],
            [
                'name' => 'web_service',
                'value' => $this->web_service
            ],
            [
                'name' => 'web_service_url',
                'value' => urlencode($this->web_service_url)
            ],
            [
                'name' => 'web_service_status',
                'value' => $this->web_service_status
            ],
            [
                'name' => 'payment',
                'value' => $payment
            ],
            [
                'name' => 'wxapp',
                'value' => $wxapp
            ],
            [
                'name' => 'quick_navigation',
                'value' => $quick_navigation
            ],
            [

                'name' => 'phone_auth',
                'value' => $this->phone_auth
            ],
            [
                'name' => 'good_negotiable',
                'value' => $good_negotiable

            ],
            [
                'name' => 'quick_map',
                'value' => $this->quick_map
            ],
            [
                'name' => 'mobile_verify',
                'value' => $this->mobile_verify,
            ]
        ];
        Option::setList($list, $this->store_id, 'admin');

        return [
            'code' => 0,
            'msg' => '保存成功',
            'attr' => $store->attributes
        ];
    }
}
