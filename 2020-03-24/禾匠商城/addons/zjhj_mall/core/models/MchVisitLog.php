<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mch_visit_log}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $mch_id
 * @property integer $addtime
 * @property string $visit_date
 */
class MchVisitLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mch_visit_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'mch_id', 'addtime', 'visit_date'], 'required'],
            [['user_id', 'mch_id', 'addtime'], 'integer'],
            [['visit_date'], 'safe'],
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
            'mch_id' => 'Mch ID',
            'addtime' => 'Addtime',
            'visit_date' => 'Visit Date',
        ];
    }
}
