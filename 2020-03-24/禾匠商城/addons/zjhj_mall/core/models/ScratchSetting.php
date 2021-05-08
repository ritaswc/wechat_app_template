<?php

namespace app\models; 

use Yii; 

/** 
 * This is the model class for table "{{%scratch_setting}}". 
 * 
 * @property integer $id
 * @property integer $store_id
 * @property integer $probability
 * @property integer $oppty
 * @property integer $type
 * @property integer $start_time
 * @property integer $end_time
 * @property string $title
 * @property string $rule
 * @property integer $deplete_register
 */ 
class ScratchSetting extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return '{{%scratch_setting}}'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['store_id', 'probability', 'oppty', 'type', 'start_time', 'end_time', 'deplete_register'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['rule'], 'string', 'max' => 1000],
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
            'probability' => '概率',
            'oppty' => '抽奖次数',
            'type' => '1.天 2 用户',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'title' => '小程序标题',
            'rule' => '规则说明',
            'deplete_register' => '消耗积分',
        ]; 
    } 
} 