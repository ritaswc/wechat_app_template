<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ms_order_comment}}".
 *
 * @property string $id
 * @property integer $store_id
 * @property integer $order_id
 * @property integer $goods_id
 * @property integer $user_id
 * @property string $score
 * @property string $content
 * @property string $pic_list
 * @property integer $is_hide
 * @property integer $is_delete
 * @property integer $addtime
 */
class MsOrderComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ms_order_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'order_id', 'goods_id', 'user_id', 'score'], 'required'],
            [['store_id', 'order_id', 'goods_id', 'user_id', 'is_hide', 'is_delete', 'addtime'], 'integer'],
            [['score'], 'number'],
            [['pic_list'], 'string'],
            [['content'], 'string', 'max' => 1000],
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
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'user_id' => 'User ID',
            'score' => '评分：1=差评，2=中评，3=好',
            'content' => '评价内容',
            'pic_list' => '图片',
            'is_hide' => '是否隐藏：0=不隐藏，1=隐藏',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
}
