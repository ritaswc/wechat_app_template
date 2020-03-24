<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ms_order_refund}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $order_id
 * @property string $order_refund_no
 * @property integer $type
 * @property string $refund_price
 * @property string $desc
 * @property string $pic_list
 * @property integer $status
 * @property string $refuse_desc
 * @property integer $addtime
 * @property integer $is_delete
 * @property integer $response_time
 */
class MsOrderRefund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ms_order_refund}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'order_id'], 'required'],
            [['store_id', 'user_id', 'order_id', 'type', 'status', 'addtime', 'is_delete', 'response_time'], 'integer'],
            [['refund_price'], 'number'],
            [['pic_list'], 'string'],
            [['order_refund_no'], 'string', 'max' => 255],
            [['desc', 'refuse_desc'], 'string', 'max' => 500],
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
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
            'order_refund_no' => '退款单号',
            'type' => '售后类型：1=退货退款，2=换货',
            'refund_price' => '退款金额',
            'desc' => '退款说明',
            'pic_list' => '凭证图片列表：json格式',
            'status' => '状态：0=待商家处理，1=同意并已退款，2=已同意换货，3=已拒绝退换货',
            'refuse_desc' => '拒绝退换货原因',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'response_time' => '商家处理时间',
        ];
    }

    public function getGoods()
    {
        return $this->hasOne(MsGoods::className(), ['id' => 'goods_id'])->alias('g')
            ->viaTable(MsOrder::tableName() . ' od', ['id' => 'order_id']);
    }
}
