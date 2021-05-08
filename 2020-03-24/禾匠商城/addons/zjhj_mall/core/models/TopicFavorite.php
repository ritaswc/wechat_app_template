<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%topic_favorite}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $topic_id
 * @property integer $addtime
 * @property integer $is_delete
 */
class TopicFavorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%topic_favorite}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'topic_id', 'addtime', 'is_delete'], 'integer'],
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
            'user_id' => 'User ID',
            'topic_id' => 'Topic ID',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }
}
