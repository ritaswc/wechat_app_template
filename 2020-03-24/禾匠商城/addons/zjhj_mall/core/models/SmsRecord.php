<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sms_record}}".
 *
 * @property integer $id
 * @property string $mobile
 * @property string $tpl
 * @property string $content
 * @property string $ip
 * @property integer $addtime
 */
class SmsRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['addtime'], 'integer'],
            [['mobile', 'tpl', 'ip'], 'string', 'max' => 255],
            [['content'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'tpl' => 'Tpl',
            'content' => 'Content',
            'ip' => 'Ip',
            'addtime' => 'Addtime',
        ];
    }
}
