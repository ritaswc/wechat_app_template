<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%store}}".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $is_delete
 * @property integer $is_recycle
 * @property integer $acid
 * @property integer $user_id
 * @property integer $wechat_platform_id
 * @property integer $wechat_app_id
 * @property string $name
 * @property string $order_send_tpl
 * @property string $contact_tel
 * @property integer $show_customer_service
 * @property string $copyright
 * @property string $copyright_pic_url
 * @property string $copyright_url
 * @property integer $delivery_time
 * @property integer $after_sale_time
 * @property integer $use_wechat_platform_pay
 * @property string $kdniao_mch_id
 * @property string $kdniao_api_key
 * @property integer $cat_style
 * @property integer $cut_thread
 * @property string $home_page_module
 * @property string $address
 * @property integer $cat_goods_cols
 * @property integer $over_day
 * @property integer $is_offline
 * @property integer $is_coupon
 * @property integer $cat_goods_count
 * @property integer $send_type
 * @property string $member_content
 * @property integer $nav_count
 * @property string $integral
 * @property string $integration
 * @property integer $dial
 * @property string $dial_pic
 * @property integer $purchase_frame
 * @property integer $is_recommend
 * @property integer $recommend_count
 * @property integer $is_comment
 * @property integer $is_sales
 * @property integer $is_member_price
 * @property integer $is_share_price
 * @property integer $buy_member
 * @property integer $logo
 * @property integer $is_official_account
 */
class Store extends \yii\db\ActiveRecord
{

    /**
     * 账号禁用状态：未禁用
     */
    const STORE_STATUS_NOT_DISABLE = 0;

    /**
     * 账号禁用状态：禁用
     */
    const STORE_STATUS_DISABLE = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'is_delete', 'is_recycle', 'acid', 'user_id', 'wechat_platform_id', 'wechat_app_id', 'show_customer_service', 'delivery_time', 'after_sale_time', 'use_wechat_platform_pay', 'cat_style', 'cut_thread', 'cat_goods_cols', 'over_day', 'is_offline', 'is_coupon', 'cat_goods_count', 'send_type', 'nav_count', 'integral', 'dial', 'purchase_frame', 'is_recommend', 'recommend_count', 'status', 'buy_member'], 'integer'],
            [['user_id', 'name'], 'required'],
            [['home_page_module', 'address', 'member_content', 'integration', 'dial_pic'], 'string'],
            [['name', 'order_send_tpl', 'contact_tel', 'copyright', 'kdniao_mch_id', 'kdniao_api_key'], 'string', 'max' => 255],
            [['copyright_pic_url', 'copyright_url'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
            'status' => '商城禁用状态 0.未禁用|1.禁用',
            'is_comment' => '商城评价开关：0.关闭 1.开启',
            'is_sales' => '商城商品销量开关：0.关闭 1.开启',
            'buy_member' => '购买会员',
        ];
    }
    public function beforeSave($insert)
    {
        if ($this->over_day === null || $this->over_day === '') {
            $this->over_day = 0;
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
