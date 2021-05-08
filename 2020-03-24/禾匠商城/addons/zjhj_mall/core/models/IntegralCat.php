<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%integral_cat}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $is_delete
 * @property integer $addtime
 * @property string $name
 * @property string $pic_url
 * @property integer $sort
 */
class IntegralCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%integral_cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_delete', 'addtime', 'name', 'pic_url'], 'required'],
            [['store_id', 'is_delete', 'addtime', 'sort'], 'integer'],
            [['pic_url'], 'string'],
            [['name'], 'string', 'max' => 50],
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
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'name' => 'Name',
            'pic_url' => 'Pic Url',
            'sort' => 'Sort',
        ];
    }

    public function getGoods()
    {
        return $this->hasMany(IntegralGoods::className(), ['cat_id'=>'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
