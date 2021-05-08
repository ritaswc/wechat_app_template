<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%user_card}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $card_id
 * @property string $card_name
 * @property string $card_pic_url
 * @property string $card_content
 * @property integer $is_use
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $clerk_id
 * @property integer $shop_id
 * @property integer $clerk_time
 * @property integer $order_id
 * @property integer $goods_id
 */
class UserCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'card_id', 'is_use', 'is_delete', 'addtime', 'clerk_id', 'shop_id', 'clerk_time', 'order_id', 'goods_id'], 'integer'],
            [['card_content'], 'string'],
            [['card_name'], 'string', 'max' => 255],
            [['card_pic_url'], 'string', 'max' => 2048],
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
            'user_id' => '用户ID',
            'card_id' => '卡券ID',
            'card_name' => '卡券名称',
            'card_pic_url' => '卡券图片',
            'card_content' => '卡券描述',
            'is_use' => '是否使用 0--未使用 1--已使用',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'clerk_id' => '核销人id',
            'shop_id' => '门店ID',
            'clerk_time' => ' 核销时间',
            'order_id' => '发放卡券的订单ID',
            'goods_id' => '商品ID',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }

    public static function deleteItem($id = null)
    {
        $model = self::findOne(['id' => $id, 'is_delete' => 0, 'store_id' => Yii::$app->store->id]);
        if (!$model) {
            return [
                'code' => 1,
                'msg' => '没有可删除的选项'
            ];
        } else {
            $model->is_delete = 1;
            if ($model->save()) {
                return [
                    'code' => 0,
                    'msg' => '删除成功'
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => $model->errors[0]
                ];
            }
        }
    }

    public static function deleteItemAll($id = null)
    {
        $count = self::updateAll(['is_delete' => 1],
            ['is_delete' => 0, 'id' => $id, 'store_id' => Yii::$app->store->id]);
        if ($count > 0) {
            return [
                'code' => 0,
                'msg' => "删除成功，共删除{$count}个"
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '没有可删除的选项'
            ];
        }
    }
}
