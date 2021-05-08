<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%card}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $pic_url
 * @property string $content
 * @property integer $is_delete
 * @property integer $addtime
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_delete', 'addtime'], 'integer'],
            [['pic_url', 'content'], 'string'],
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
            'name' => '卡券名称',
            'pic_url' => '卡券图片',
            'content' => '卡券描述',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
    public function beforeSave($insert)
    {
        $this->name = \yii\helpers\Html::encode($this->name);
        $this->content = \yii\helpers\Html::encode($this->content);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        $title = '卡券';
        CommonActionLog::storeActionLog($title, $insert, $this->is_delete, $data, $this->id);
    }
}
