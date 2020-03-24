<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%order_share}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $type
 * @property integer $order_id
 * @property integer $parent_id_1
 * @property integer $parent_id_2
 * @property integer $parent_id_3
 * @property string $first_price
 * @property string $second_price
 * @property string $third_price
 * @property integer $is_delete
 * @property string $version
 * @property string $rebate
 * @property integer $user_id
 */
class OrderShare extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_share}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'type', 'order_id', 'parent_id_1', 'parent_id_2', 'parent_id_3', 'is_delete', 'user_id'], 'integer'],
            [['first_price', 'second_price', 'third_price', 'rebate'], 'number'],
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
            'type' => '类型 0--拼团 1--错误数据 2--预约',
            'order_id' => '订单id',
            'parent_id_1' => '一级分销商id',
            'parent_id_2' => '二级分销商id',
            'parent_id_3' => '三级分销商id',
            'first_price' => '一级分销佣金',
            'second_price' => '二级分销佣金',
            'third_price' => '三级分销佣金',
            'is_delete' => 'Is Delete',
            'version' => 'Version',
            'rebate' => '自购返利',
            'user_id' => '下单用户id',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
