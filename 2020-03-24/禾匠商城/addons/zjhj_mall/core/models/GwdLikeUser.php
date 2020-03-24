<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hjmall_gwd_like_user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $like_id
 * @property integer $is_delete
 */
class GwdLikeUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gwd_like_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'like_id'], 'required'],
            [['user_id', 'like_id', 'is_delete'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'like_id' => 'Like ID',
            'is_delete' => 'Is Delete',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
