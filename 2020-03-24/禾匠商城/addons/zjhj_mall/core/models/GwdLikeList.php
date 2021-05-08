<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hjmall_gwd_like_list".
 *
 * @property integer $id
 * @property integer $good_id
 * @property integer $store_id
 * @property integer $is_delete
 * @property integer $type
 * @property string $addtime
 */
class GwdLikeList extends \yii\db\ActiveRecord
{
    const TYPE_STORE = 0;
    const TYPE_MS = 1;
    const TYPE_PT = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gwd_like_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['good_id', 'store_id', 'type'], 'required'],
            [['good_id', 'is_delete', 'store_id', 'type'], 'integer'],
            [['addtime'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'good_id' => 'Good ID',
            'store_id' => 'Store ID',
            'is_delete' => 'Is Delete',
            'type' => 'Type',
            'addtime' => 'Addtime',
        ];
    }

    public function getUsers()
    {
//        return $this->hasMany(GwdLikeUser::className(), ['like_id' => 'id']);
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->via('glu');
    }

    public function getGlu() {
        return $this->hasMany(GwdLikeUser::className(), ['like_id' => 'id']);
    }

    public function getGood()
    {
        return $this->hasOne(Goods::className(), ['id' => 'good_id']);
    }

}
