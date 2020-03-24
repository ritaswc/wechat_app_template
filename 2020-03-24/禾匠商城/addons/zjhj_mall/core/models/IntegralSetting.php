<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%integral_setting}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $integral_shuoming
 * @property string $register_rule
 * @property integer $register_integral
 * @property integer $register_continuation
 * @property string $register_reward
 */
class IntegralSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%integral_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'register_rule', 'register_integral', 'register_continuation', 'register_reward'], 'required'],
            [['store_id', 'register_integral', 'register_continuation','register_reward'], 'integer'],
            [[ 'register_rule'], 'string'],
            [['setting','attr','integral_shuoming'], 'safe'],
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
            'integral_shuoming' => 'Integral Shuoming',
            'register_rule' => 'Register Rule',
            'register_integral' => 'Register Integral',
            'register_continuation' => 'Register Continuation',
            'register_reward' => 'Register Reward',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, 0, $data, $this->id);
    }
}
