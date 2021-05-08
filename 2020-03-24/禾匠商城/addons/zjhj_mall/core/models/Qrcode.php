<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%qrcode}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $qrcode_bg
 * @property string $avatar_size
 * @property string $avatar_position
 * @property string $qrcode_size
 * @property string $qrcode_position
 * @property string $font_position
 * @property string $font
 * @property string $preview
 * @property integer $is_delete
 * @property integer $addtime
 */
class Qrcode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%qrcode}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'qrcode_bg', 'avatar_size', 'avatar_position', 'qrcode_size', 'qrcode_position', 'font_position', 'font', 'preview'], 'required'],
            [['store_id', 'is_delete', 'addtime'], 'integer'],
            [['qrcode_bg', 'font', 'preview'], 'string'],
            [['avatar_size', 'avatar_position', 'qrcode_size', 'qrcode_position', 'font_position'], 'string', 'max' => 255],
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
            'qrcode_bg' => '背景图片',
            'avatar_size' => '头像大小{\"w\":\"\",\"h\":\"\"}',
            'avatar_position' => '头像坐标{\"x\":\"\",\"y\":\"\"}',
            'qrcode_size' => '二维码大小{\"w\":\"\",\"h\":\"\"}',
            'qrcode_position' => '二维码坐标{\"x\":\"\",\"y\":\"\"}',
            'font_position' => '字体坐标{\"x\":\"\",\"y\":\"\"}',
            'font' => '字体设置
{
  \"size\":大小,
  \"color\":\"r,g,b\"
}',
            'preview'=>'预览图',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
}
