<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%goods_cat}}".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $store_id
 * @property integer $cat_id
 * @property integer $addtime
 * @property integer $is_delete
 */
class GoodsCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%goods_cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'store_id', 'cat_id', 'addtime', 'is_delete'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'store_id' => 'Store ID',
            'cat_id' => '分类id  多个分类用英文逗号隔开',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }
//    关联商品表
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id'=>'goods_id']);
    }

    public function getCat()
    {
        return $this->hasOne(Cat::className(), ['id' => 'cat_id'])->where(['is_delete' => 0]);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
