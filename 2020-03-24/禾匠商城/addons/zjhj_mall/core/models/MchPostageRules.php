<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mch_postage_rules}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property string $name
 * @property integer $express_id
 * @property string $detail
 * @property integer $addtime
 * @property integer $is_enable
 * @property integer $is_delete
 * @property string $express
 * @property integer $type
 */
class MchPostageRules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mch_postage_rules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'name', 'express_id', 'detail'], 'required'],
            [['mch_id', 'express_id', 'addtime', 'is_enable', 'is_delete', 'type'], 'integer'],
            [['detail'], 'string'],
            [['name', 'express'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mch_id' => 'Mch ID',
            'name' => '名称',
            'express_id' => '物流公司',
            'detail' => '规则详细',
            'addtime' => 'Addtime',
            'is_enable' => '是否启用：0=否，1=是',
            'is_delete' => 'Is Delete',
            'express' => '快递公司',
            'type' => '计费方式【1=>按重计费、2=>按件计费】',
        ];
    }
}
