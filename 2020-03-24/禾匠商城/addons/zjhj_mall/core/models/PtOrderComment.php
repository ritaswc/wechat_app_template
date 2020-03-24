<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pt_order_comment}}".
 *
 * @property string $id
 * @property integer $store_id
 * @property integer $order_id
 * @property integer $order_detail_id
 * @property integer $goods_id
 * @property integer $user_id
 * @property string $score
 * @property string $content
 * @property string $pic_list
 * @property integer $is_hide
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $is_virtual
 * @property string $virtual_user
 * @property string $virtual_avatar
 */
class PtOrderComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pt_order_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'order_id', 'order_detail_id', 'goods_id', 'user_id', 'score'], 'required'],
            [['store_id', 'order_id', 'order_detail_id', 'goods_id', 'user_id', 'is_hide', 'is_delete', 'addtime', 'is_virtual'], 'integer'],
            [['score'], 'number'],
            [['pic_list'], 'string'],
            [['content'], 'string', 'max' => 1000],
            [['virtual_user', 'virtual_avatar'], 'string', 'max' => 255],
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
            'order_detail_id' => 'Order Detail ID',
            'goods_id' => 'Goods ID',
            'user_id' => 'User ID',
            'score' => '评分：1=差评，2=中评，3=好',
            'content' => '评价内容',
            'pic_list' => '图片',
            'is_hide' => '是否隐藏：0=不隐藏，1=隐藏',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'is_virtual' => '是否客户评价',
            'virtual_user' => 'Virtual User',
            'virtual_avatar' => 'Virtual Avatar',
        ];
    }

    public static function getCount($goods_id = 0, $store_id)
    {
        return PtOrderComment::find()
            ->andWhere(['store_id'=>$store_id,'goods_id'=>$goods_id,'is_delete'=>0,'is_hide'=>0])
            ->count();
    }
    
    public function getGoods()
    {
        return $this->hasOne(PtGoods::className(), ['id'=>'goods_id']);
    }
}
