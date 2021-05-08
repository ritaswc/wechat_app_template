<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%topic_type}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property integer $sort
 * @property integer $is_delete
 */
class TopicType extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%topic_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sort', 'is_delete'], 'required'],
            [['store_id', 'sort', 'is_delete'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'sort' => 'Sort',
            'is_delete' => 'Is Delete',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
