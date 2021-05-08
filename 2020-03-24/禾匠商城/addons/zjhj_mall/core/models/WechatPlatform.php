<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%wechat_platform}}".
 *
 * @property integer $id
 * @property integer $acid
 * @property integer $user_id
 * @property string $name
 * @property string $app_id
 * @property string $app_secret
 * @property string $desc
 * @property string $mch_id
 * @property string $key
 * @property string $cert_pem
 * @property string $key_pem
 * @property integer $addtime
 * @property integer $is_delete
 */
class WechatPlatform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wechat_platform}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['acid', 'user_id', 'addtime', 'is_delete'], 'integer'],
            [['user_id', 'name', 'app_id', 'app_secret'], 'required'],
            [['cert_pem', 'key_pem'], 'string'],
            [['name', 'app_id', 'app_secret', 'desc', 'mch_id', 'key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acid' => '微擎公众号id',
            'user_id' => 'User ID',
            'name' => '公众号名称',
            'app_id' => '公众号appid',
            'app_secret' => '公众号appsecret',
            'desc' => '公共号说明、备注',
            'mch_id' => '微信支付商户号',
            'key' => '微信支付key',
            'cert_pem' => '微信支付cert文件内容',
            'key_pem' => '微信支付key文件内容',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }
}
