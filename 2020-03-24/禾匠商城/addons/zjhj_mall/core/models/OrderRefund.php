<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Codeception\PHPUnit\ResultPrinter\HTML;
use Yii;

/**
 * This is the model class for table "{{%order_refund}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $order_id
 * @property integer $order_detail_id
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
 * @property integer $is_agree
 * @property integer $is_user_send
 * @property string $user_send_express
 * @property string $user_send_express_no
 * @property integer $address_id
 */
class OrderRefund extends \yii\db\ActiveRecord
{
    /**
     * 售后类型：退货退款
     */
    const TYPE_REFUND = 1;

    /**
     * 售后类型：换货
     */
    const TYPE_EXCHANGE = 2;

    /**
     * 售后状态：商家待处理
     */
    const STATUS_IN = 0;

    /**
     * 售后状态：商家同意并已退款
     */
    const STATUS_REFUND_AGREE = 1;

    /**
     * 售后状态：商家同意换货
     */
    const STATUS_EXCHANGE_AGREE = 2;

    /**
     * 售后状态：商家拒绝退换货
     */
    const STATUS_REFUSE = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_refund}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'order_id', 'order_detail_id'], 'required'],
            [['store_id', 'user_id', 'order_id', 'order_detail_id', 'type', 'status', 'addtime', 'is_delete', 'response_time', 'is_agree', 'is_user_send', 'address_id'], 'integer'],
            [['refund_price'], 'number'],
            [['pic_list'], 'string'],
            [['order_refund_no'], 'string', 'max' => 255],
            [['desc', 'refuse_desc'], 'string', 'max' => 500],
            [['user_send_express', 'user_send_express_no'], 'string', 'max' => 32],
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
            'order_detail_id' => 'Order Detail ID',
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
            'is_agree' => '是否已同意退、换货：0=待处理，1=已同意，2=已拒绝',
            'is_user_send' => '用户已发货：0=未发货，1=已发货',
            'user_send_express' => '用户发货快递公司',
            'user_send_express_no' => '用户发货快递单号',
            'address_id' => '退货 换货地址id',
        ];
    }

    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id'])->alias('g')
            ->viaTable(OrderDetail::tableName() . ' od', ['id' => 'order_detail_id']);
    }

    public function beforeSave($insert)
    {
        $this->desc = \yii\helpers\Html::encode($this->desc);
        $this->user_send_express = \yii\helpers\Html::encode($this->user_send_express);
        $this->user_send_express_no = \yii\helpers\Html::encode($this->user_send_express_no);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
