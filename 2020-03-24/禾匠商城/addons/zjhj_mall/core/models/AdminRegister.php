<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%admin_register}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $mobile
 * @property string $name
 * @property string $desc
 * @property integer $addtime
 * @property integer $status
 * @property integer $is_delete
 */
class AdminRegister extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_register}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'mobile', 'name', 'addtime'], 'required'],
            [['addtime', 'status', 'is_delete'], 'integer'],
            [['username'], 'string', 'max' => 32],
            [['password', 'name'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 15],
            [['desc'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '帐户名',
            'password' => '密码',
            'mobile' => '手机号',
            'name' => '姓名/企业名',
            'desc' => '申请原因',
            'addtime' => 'Addtime',
            'status' => '审核状态：0=待审核，1=通过，2=不通过',
            'is_delete' => 'Is Delete',
        ];
    }

    public function beforeSave($insert)
    {
        $this->name = Html::encode($this->name);
        $this->desc = Html::encode($this->desc);
        return parent::beforeSave($insert);
    }
}
