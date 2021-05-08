<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%mail_setting}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $send_mail
 * @property string $send_pwd
 * @property string $send_name
 * @property string $receive_mail
 * @property integer $status
 * @property integer $is_delete
 * @property integer $addtime
 */
class MailSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mail_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'status', 'is_delete', 'addtime'], 'integer'],
            [['send_mail', 'receive_mail'], 'string'],
            [['send_pwd', 'send_name'], 'string', 'max' => 255],
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
            'send_mail' => '发件人邮箱',
            'send_pwd' => '授权码',
            'send_name' => '发件人名称',
            'receive_mail' => '收件人邮箱 多个用英文逗号隔开',
            'status' => '是否开启邮件通知 0--关闭 1--开启',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
