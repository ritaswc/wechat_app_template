<?php

namespace app\models; 

use Yii; 

/** 
 * This is the model class for table "{{%step_activity}}". 
 * 
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $currency
 * @property string $bail_currency
 * @property integer $step_num
 * @property string $open_date
 * @property integer $is_delete
 * @property integer $type
 * @property integer $create_time
 * @property integer $status
 */ 
class StepActivity extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return '{{%step_activity}}'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['store_id', 'step_num', 'is_delete', 'type', 'create_time', 'status'], 'integer'],
            [['currency', 'bail_currency'], 'number'],
            [['open_date'], 'required'],
            [['open_date'], 'safe'],
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
            'name' => '活动标题',
            'currency' => '奖金池',
            'bail_currency' => '保证金',
            'step_num' => '挑战布数',
            'open_date' => '开放日期',
            'is_delete' => '是否删除',
            'type' => '0进行中 1 已完成',
            'create_time' => '创建时间',
            'status' => '状态',
        ]; 
    }
    
    public function getLog()
    {
        return $this->hasMany(StepLog::className(), ['type_id' => 'id']);
    }

} 