<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\user;

use app\models\AuthRoleUser;
use app\models\User;
use app\modules\mch\models\MchModel;
use Yii;

class UpdatePasswordForm extends MchModel
{
    public $userId;
    public $password;

    public function rules()
    {
        return [
            [['password', 'userId'], 'required'],
            [['role'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => '新密码',
            'userId' => '用户ID',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $user = User::find()->where(['id' => $this->userId])->one();
        if (!$user) {
            return [
                'code' => 1,
                'msg' => '用户不存在'
            ];
        }

        $user->password = \Yii::$app->security->generatePasswordHash($this->password, 5);
        $user = $user->save();

        if ($user) {
            return [
                'code' => 0,
                'msg' => '更新成功'
            ];
        }
    }
}
