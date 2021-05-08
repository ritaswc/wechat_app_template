<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%lottery_reserve}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $lottery_id
 * @property integer $addtime
 */
class LotteryReserve extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lottery_reserve}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'lottery_id', 'addtime'], 'integer'],
            [['user_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'addtime' => 'Addtime',
            'store_id' => 'Store ID',
            'user_id' => '用户',
            'lottery_id' => '奖品',
        ];
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function userAdd($user_id, $lottery_id)
    {
        $user = User::findOne($user_id);
        if (!$user) {
            return false;
        }

        $lottery = LotteryGoods::findOne([
            'id' => $lottery_id,
            'is_delete' => 0,
        ]);
        if (!$lottery) {
            return false;
        }

        $reserve = LotteryReserve::findOne([
            'user_id' => $user->id,
            'lottery_id' => $lottery->id
        ]);
        if($reserve){
            return false;
        }
        $reserve = new LotteryReserve();
        $reserve->store_id = $user->store_id;
        $reserve->user_id = $user->id;
        $reserve->addtime = time();
        $reserve->lottery_id = $lottery->id;     

        return $reserve->save();
    }
}
