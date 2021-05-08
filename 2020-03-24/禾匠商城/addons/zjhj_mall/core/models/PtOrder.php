<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%pt_order}}".
 *
 * @property string $id
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
 * @property string $address_data
 * @property integer $is_group
 * @property string $parent_id
 * @property string $colonel
 * @property integer $is_success
 * @property string $success_time
 * @property integer $status
 * @property integer $is_returnd
 * @property integer $is_cancel
 * @property integer $limit_time
 * @property integer $content
 * @property integer $words
 * @property integer $shop_id
 * @property integer $offline
 * @property integer $clerk_id
 * @property integer $is_price
 * @property string $express_price_1
 * @property integer $class_group
 * @property integer $is_recycle
 * @property integer $is_show
 * @property string $seller_comments
 */
class PtOrder extends \yii\db\ActiveRecord
{
    public $is_offline;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pt_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'order_no', 'is_group'], 'required'],
            [['store_id', 'user_id', 'is_pay', 'pay_type', 'pay_time', 'is_send', 'send_time', 'is_confirm', 'confirm_time', 'is_comment', 'apply_delete', 'addtime', 'is_delete', 'is_group', 'parent_id', 'is_success', 'success_time', 'status', 'is_returnd', 'is_cancel', 'limit_time', 'shop_id', 'offline', 'clerk_id', 'is_price', 'class_group', 'is_recycle', 'is_show'], 'integer'],
            [['total_price', 'pay_price', 'express_price', 'colonel', 'express_price_1'], 'number'],
            [['address_data', 'content', 'words', 'seller_comments'], 'string'],
            [['order_no', 'name', 'mobile', 'express', 'express_no'], 'string', 'max' => 255],
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
            'pay_type' => '支付方式：1=微信支付',
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
            'address_data' => '收货地址信息，json格式',
            'is_group' => '是否团购',
            'parent_id' => '团ID【0=> 团长ID】',
            'colonel' => '团长优惠',
            'is_success' => '是否成团',
            'success_time' => '成团时间',
            'status' => '拼团状态【1=> 待付款，2= 拼团中，3=拼团成功，4=拼团失败】',
            'is_returnd' => '是否退款',
            'is_cancel' => '是否取消',
            'limit_time' => '拼团限时',
            'content' => '留言',
            'words' => '商家留言',
            'shop_id' => '自提店铺',
            'offline' => '拿货方式',
            'clerk_id' => '核销员ID',
            'is_price' => '是否发放佣金',
            'express_price_1' => '减免的运费',
            'class_group' => '阶级团',
            'is_recycle' => '是否加入回收站 0--不加入 1--加入',
            'is_show' => '是否显示 0--不显示 1--显示（软删除）',
            'seller_comments' => '商家备注',
        ];
    }

    /**
     * @return string
     * 拼团剩余人数
     */
    public function getSurplusGruop()
    {
        if ($this->is_group != 1) {
            return;
        }
        // 先验证当前订单是参团还是拼团
        if ($this->parent_id == 0) { // 拼团
            $order_id = $this->id;
        } else {
            $order_id = $this->parent_id;
        }

        $order_detail = PtOrderDetail::findOne(['order_id' => $order_id, 'is_delete' => 0]);
        if ($this->class_group) {
            $goods = PtGoodsDetail::findOne(['id' => $this->class_group]);
        } else {
            $goods = PtGoods::findOne(['id' => $order_detail->goods_id]);
        }
        $groupNum = PtOrder::find()
            ->andWhere(['or', ['id' => $order_id], ['parent_id' => $order_id]])
            ->andWhere(['status' => 2, 'is_group' => 1])
            ->andWhere([
                'OR',
                ['is_pay' => 1],
                ['pay_type' => 2]
            ])
            ->count();
        return $goods->group_num - $groupNum;
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
        return $this->hasMany(PtOrderDetail::className(), ['order_id'=>'id'])->alias('od')
            ->leftJoin(['g'=>PtGoods::tableName()], 'g.id=goods_id')->select(['od.*','g.name','g.attr goods_attr']);
    }

    // 获取指定商品指定用户已购买次数
    public static function getCount($goods_id,$user_id)
    {
        $query = PtOrderDetail::find()->where(['is_delete'=>0,'goods_id'=>$goods_id]);
        $orderNum = PtOrder::find()
            ->alias('o')
            ->andWhere(['o.user_id'=>$user_id,'o.is_delete'=>0,'o.is_pay'=>1,'o.is_group'=>1])
            ->andWhere(['OR',['o.status'=>2],['o.status'=>3]])
            ->innerJoin(['od'=>$query],'od.order_id=o.id')
            ->andWhere(['!=','o.order_no','robot'])
            ->count();
        return $orderNum;
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
        return $this->hasMany(PtOrderRefund::className(), ['order_id' => 'id']);
    }
}
