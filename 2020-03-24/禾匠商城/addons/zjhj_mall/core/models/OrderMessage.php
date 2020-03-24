<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%order_message}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $order_id
 * @property integer $is_read
 * @property integer $is_sound
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $type
 * @property integer $order_type
 */
class OrderMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'order_id', 'is_read', 'is_sound', 'is_delete', 'addtime', 'type', 'order_type'], 'integer'],
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
            'order_id' => '类型id 系统消息时为0',
            'is_read' => '消息是否已读 0--未读 1--已读',
            'is_sound' => '是否提示 0--未提示  1--已提示',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'type' => '订单类型  0--已下订单   1--售后订单',
            'order_type' => '订单类型 0--商城订单 1--秒杀订单 2--拼团订单 3--预约订单',
        ];
    }

    /**
     * @param int $order_id 订单id
     * @param int $store_id
     * @param int $order_type 订单类型 0--商城订单 1--秒杀订单 2--拼团订单 3--预约订单 4--商户提交审核
     * @param int $type 类型  0--已下订单   1--售后订单
     * @return bool
     */
    public static function set($order_id, $store_id = 0, $order_type = 0, $type = 0)
    {
        if (empty($order_id)) {
            return false;
        }
        $model = OrderMessage::findOne([
            'store_id' => $store_id,
            'order_id' => $order_id,
            'type' => $type,
            'order_type'=>$order_type
        ]);
        if (!$model) {
            $model = new OrderMessage();
            $model->order_id = $order_id;
            $model->store_id = $store_id;
            $model->order_type = $order_type;
            $model->type = $type;
            $model->is_delete = 0;
            $model->is_read = 0;
            $model->is_sound = 0;
            $model->addtime = time();
        }
        return $model->save();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
