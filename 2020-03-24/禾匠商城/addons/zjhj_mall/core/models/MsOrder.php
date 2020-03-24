<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%ms_order}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $order_no
 * @property string $total_price
 * @property string $pay_price
 * @property string $express_price
 * @property string $name
 * @property string $mobile
 * @property string $address
 * @property string $remark
 * @property integer $is_pay
 * @property integer $pay_type
 * @property integer $pay_time
 * @property integer $is_send
 * @property integer $send_time
 * @property string $express
 * @property string $express_no
 * @property integer $is_confirm
 * @property integer $confirm_time
 * @property integer $is_comment
 * @property integer $apply_delete
 * @property integer $addtime
 * @property integer $is_delete
 * @property integer $is_price
 * @property integer $parent_id
 * @property string $first_price
 * @property string $second_price
 * @property string $third_price
 * @property string $coupon_sub_price
 * @property string $address_data
 * @property string $content
 * @property integer $is_offline
 * @property integer $clerk_id
 * @property integer $is_cancel
 * @property string $offline_qrcode
 * @property string $before_update_price
 * @property integer $shop_id
 * @property string $discount
 * @property integer $user_coupon_id
 * @property string $integral
 * @property integer $give_integral
 * @property integer $parent_id_1
 * @property integer $parent_id_2
 * @property integer $is_sale
 * @property string $words
 * @property string $express_price_1
 * @property integer $goods_id
 * @property string $attr
 * @property string $pic
 * @property string $integral_amount
 * @property string $num
 * @property string $limit_time
 * @property integer $is_sum
 * @property string $rebate
 * @property string $before_update_express
 * @property integer $is_recycle
 * @property integer $is_show
 * @property integer $attr
 * @property integer $seller_comments
 */
class MsOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ms_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'order_no', 'first_price', 'second_price', 'third_price', 'goods_id', 'attr', 'pic'], 'required'],
            [['store_id', 'user_id', 'is_pay', 'pay_type', 'pay_time', 'is_send', 'send_time', 'is_confirm', 'confirm_time', 'is_comment', 'apply_delete', 'addtime', 'is_delete', 'is_price', 'parent_id', 'is_offline', 'clerk_id', 'is_cancel', 'shop_id', 'user_coupon_id', 'give_integral', 'parent_id_1', 'parent_id_2', 'is_sale', 'goods_id', 'integral_amount', 'num', 'limit_time', 'is_sum', 'is_recycle', 'is_show'], 'integer'],
            [['total_price', 'pay_price', 'express_price', 'first_price', 'second_price', 'third_price', 'coupon_sub_price', 'before_update_price', 'discount', 'express_price_1', 'rebate', 'before_update_express'], 'number'],
            [['address_data', 'content', 'offline_qrcode', 'integral', 'words', 'attr', 'seller_comments'], 'string'],
            [['order_no', 'name', 'mobile', 'express', 'express_no', 'pic'], 'string', 'max' => 255],
            [['address', 'remark'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'user_id' => '用户id',
            'order_no' => '订单号',
            'total_price' => '订单总费用(包含运费）',
            'pay_price' => '实际支付总费用(含运费）',
            'express_price' => '运费',
            'name' => '收货人姓名',
            'mobile' => '收货人手机',
            'address' => '收货地址',
            'remark' => '订单备注',
            'is_pay' => '支付状态：0=未支付，1=已支付',
            'pay_type' => '支付方式：1=微信支付 2--货到付款',
            'pay_time' => '支付时间',
            'is_send' => '发货状态：0=未发货，1=已发货',
            'send_time' => '发货时间',
            'express' => '物流公司',
            'express_no' => 'Express No',
            'is_confirm' => '确认收货状态：0=未确认，1=已确认收货',
            'confirm_time' => '确认收货时间',
            'is_comment' => '是否已评价：0=未评价，1=已评价',
            'apply_delete' => '是否申请取消订单：0=否，1=申请取消订单',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'is_price' => '是否发放佣金',
            'parent_id' => '用户上级ID',
            'first_price' => '一级佣金',
            'second_price' => '二级佣金',
            'third_price' => '三级佣金',
            'coupon_sub_price' => '优惠券抵消金额',
            'address_data' => '收货地址信息，json格式',
            'content' => 'Content',
            'is_offline' => '是否到店自提 0--否 1--是',
            'clerk_id' => '核销员user_id',
            'is_cancel' => '是否取消',
            'offline_qrcode' => '核销码',
            'before_update_price' => '修改前的价格',
            'shop_id' => '自提门店ID',
            'discount' => '会员折扣',
            'user_coupon_id' => '使用的优惠券ID',
            'integral' => '积分使用',
            'give_integral' => '是否发放积分【1=> 已发放 ， 0=> 未发放】',
            'parent_id_1' => '用户上二级ID',
            'parent_id_2' => '用户上三级ID',
            'is_sale' => '是否超过售后时间',
            'words' => '商家留言',
            'express_price_1' => '减免的运费',
            'goods_id' => 'Goods ID',
            'attr' => '商品规格',
            'pic' => '商品规格图片',
            'integral_amount' => '单品积分获得',
            'num' => '商品数量',
            'limit_time' => '可支付截止时间',
            'is_sum' => '是否计算分销 0--不计算 1--计算',
            'rebate' => '自购返利',
            'before_update_express' => '价格修改前的运费',
            'is_recycle' => '是否在回收站 0--不在回收站 1--在回收站',
            'is_show' => '是否显示 0--不显示 1--显示（软删除用）',
            'attr' => '规格',
            'seller_comments' => '商家备注',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getClerk()
    {
        return $this->hasOne(User::className(), ['id' => 'clerk_id']);
    }

    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }

    public function getOrderDetail()
    {
        return $this->hasMany(MsOrder::className(), ['id' => 'id'])->alias('od')
            ->leftJoin(['g' => MsGoods::tableName()], 'g.id=goods_id')->select(['od.*', 'g.name', 'g.attr goods_attr']);
    }

    public function getGood()
    {
        return $this->hasOne(MsGoods::className(), ['id' => 'goods_id']);
    }


    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return Yii::createObject(MyActiveQuery::className(), [
            get_called_class(), [
                'myCondition' => [
                    'is_show' => 1
                ]
            ]
        ]);
    }

    public function getRefund()
    {
        return $this->hasMany(MsOrderRefund::className(), ['order_id' => 'id']);
    }
}
