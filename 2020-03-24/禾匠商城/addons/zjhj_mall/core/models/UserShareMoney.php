<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_share_money}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $type
 * @property integer $source
 * @property string $money
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $order_type
 * @property string $version
 */
class UserShareMoney extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_share_money}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'order_id', 'user_id', 'type', 'source', 'is_delete', 'addtime', 'order_type'], 'integer'],
            [['money'], 'number'],
            [['version'], 'string', 'max' => 255],
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
            'order_id' => '订单ID',
            'user_id' => '用户ID',
            'type' => '类型 0--佣金 1--提现',
            'source' => '佣金来源 1--一级分销 2--二级分销 3--三级分销',
            'money' => '金额',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'order_type' => '订单类型 0--商城订单 1--秒杀订单 2--拼团订单 3--预约订单',
            'version' => '版本',
        ];
    }

    public static function set($money, $user_id, $order_id, $type, $source = 1, $store_id = 0, $order_type = 0)
    {
        $model = new UserShareMoney();
        $model->store_id = $store_id;
        $model->order_id = $order_id;
        $model->user_id = $user_id;
        $model->type = $type;
        $model->source = $source;
        $model->money = $money;
        $model->is_delete = 0;
        $model->addtime = time();
        $model->order_type = $order_type;
        $model->version = hj_core_version();
        return $model->save();
    }
}
