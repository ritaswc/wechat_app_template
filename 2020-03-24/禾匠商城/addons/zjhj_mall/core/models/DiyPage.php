<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%diy_page}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $title
 * @property integer $template_id
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $status
 * @property integer $is_index
 */
class DiyPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%diy_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'template_id', 'is_delete', 'addtime', 'status', 'is_index'], 'integer'],
            [['template_id', 'addtime'], 'required'],
            [['title'], 'string', 'max' => 45],
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
            'title' => '页面标题',
            'template_id' => '模板ID',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'status' => '状态 0--禁用 1--启用',
            'is_index' => '是否覆盖首页 0--不覆盖 1--覆盖',
        ];
    }

    public function getTemplate()
    {
        return $this->hasOne(DiyTemplate::className(), ['id' => 'template_id']);
    }
}
