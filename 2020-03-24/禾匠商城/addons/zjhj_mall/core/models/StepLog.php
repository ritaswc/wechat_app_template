<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%step_log}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $create_time
 * @property integer $num
 * @property string $step_currency
 * @property integer $step_id
 * @property integer $type
 * @property integer $order_id
 * @property integer $status
 */
class StepLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%step_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'create_time', 'num', 'step_id', 'type', 'type_id', 'status'], 'integer'],
            [['step_currency'], 'number'],
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
            'create_time' => '创建时间',
            'num' => '兑换布数',
            'step_currency' => '活力币',
            'step_id' => 'User ID',
            'type' => '1兑换 2支出 ',
            'type_id' => '订单ID',
            'status' => 'status'
        ];
    }

    public function getStep()
    {
        return $this->hasOne(StepUser::className(), ['id' => 'step_id']);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'type_id']);
    }

    public function getActivity()
    {
        return $this->hasOne(StepActivity::className(), ['id' => 'type_id']);
    }
}
