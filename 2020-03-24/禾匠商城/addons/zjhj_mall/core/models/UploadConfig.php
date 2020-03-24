<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%upload_config}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $storage_type
 * @property string $aliyun
 * @property string $qcloud
 * @property string $qiniu
 * @property integer $addtime
 * @property integer $is_delete
 */
class UploadConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%upload_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'addtime', 'is_delete'], 'integer'],
            [['aliyun', 'qcloud', 'qiniu'], 'string'],
            [['storage_type'], 'string', 'max' => 255],
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
            'storage_type' => '存储类型：留空=本地，aliyun=阿里云oss，qcloud=腾讯云cos，qiniu=七牛云存储',
            'aliyun' => '阿里云oss配置，json格式',
            'qcloud' => '腾讯云cos配置，json格式',
            'qiniu' => '七牛云存储配置，json格式',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }
}
