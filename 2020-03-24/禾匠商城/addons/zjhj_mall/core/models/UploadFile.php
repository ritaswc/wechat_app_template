<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%upload_file}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $file_url
 * @property string $extension
 * @property string $type
 * @property integer $size
 * @property integer $addtime
 * @property integer $is_delete
 * @property integer $group_id
 * @property integer $mch_id
 */
class UploadFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%upload_file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'size', 'addtime', 'is_delete', 'group_id', 'mch_id'], 'integer'],
            [['file_url'], 'string'],
            [['extension', 'type'], 'string', 'max' => 255],
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
            'file_url' => '文件url',
            'extension' => '文件扩展名',
            'type' => '文件类型：',
            'size' => '文件大小，字节',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'group_id' => '分组id',
            'mch_id' => '商户id',
        ];
    }
}
