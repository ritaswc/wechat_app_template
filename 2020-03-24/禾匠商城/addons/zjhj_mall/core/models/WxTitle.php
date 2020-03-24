<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%wx_title}}".
 *
 * @property integer $id
 * @property string $url
 * @property integer $store_id
 * @property string $title
 * @property string $addtime
 */
class WxTitle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_title}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'integer'],
            [['addtime'], 'required'],
            [['addtime'], 'safe'],
            [['url', 'title'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'store_id' => 'Store ID',
            'title' => 'Title',
            'addtime' => 'Addtime',
        ];
    }
}
