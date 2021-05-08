<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%printer_setting}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $printer_id
 * @property integer $block_id
 * @property string $type
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $is_attr
 * @property integer $big
 */
class PrinterSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%printer_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'printer_id', 'block_id', 'is_delete', 'addtime', 'is_attr', 'big'], 'integer'],
            [['type'], 'string'],
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
            'printer_id' => '打印机ID',
            'block_id' => '打印模板ID',
            'type' => '打印方式 ',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'is_attr' => '是否打印规格 0--不打印 1--打印',
            'big' => '发大倍数',
        ];
    }
}
