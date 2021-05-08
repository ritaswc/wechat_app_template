<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/3
 * Time: 14:52
 */

namespace app\modules\admin\models;

use app\models\Admin;

/**
 * @property Admin $admin
 */
class UserEditForm extends AdminModel
{
    public $admin;
    public $username;
    public $password;
    public $remark;
    public $app_max_count;
    public $permission;
    public $expire_time;
    public $no_expire_time;
    public $mobile;

    public function rules()
    {
        return [
            [['username', 'remark', 'mobile',], 'trim'],
            [['username', 'password'], 'required', 'on' => 'add'],
            [['app_max_count'], 'integer', 'min' => 0,],
            [['app_max_count'], 'required',],
            [['remark'], 'string', 'length' => [0, 255]],
            [['permission', 'expire_time', 'no_expire_time',], 'safe',],
            [['mobile',], 'match', 'pattern' => '/^1[3456789]{1}\d{9}$/',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '登录密码',
            'remark' => '备注',
            'app_max_count' => '小程序数量',
            'mobile' => '手机号',
        ];
    }


    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        if ($this->mobile) {
            if ($this->admin->isNewRecord) {
                $exist_mobile = Admin::find()->where([
                    'mobile' => $this->mobile,
                    'is_delete' => 0,
                ])->exists();
            } else {
                $exist_mobile = Admin::find()->where([
                    'AND',
                    ['mobile' => $this->mobile,],
                    ['is_delete' => 0,],
                    ['!=', 'id', $this->admin->id,],
                ])->exists();
            }
            if ($exist_mobile) {
                return [
                    'code' => 1,
                    'msg' => '该手机号已被使用，请更换其它手机号。',
                ];
            }
        }

        if (!$this->permission || !is_array($this->permission)) {
            $this->permission = [];
        }
        $this->admin->mobile = $this->mobile;
        $this->admin->remark = $this->remark;
        $this->admin->app_max_count = $this->app_max_count;
        $this->admin->permission = json_encode($this->permission, JSON_UNESCAPED_UNICODE);
        if ($this->no_expire_time) {
            $this->admin->expire_time = 0;
        } else {
            $this->admin->expire_time = strtotime($this->expire_time . ' 23:59:59');
        }

        if ($this->admin->isNewRecord) {
            $exist_admin = Admin::findOne([
                'username' => $this->username,
                'is_delete' => 0,
            ]);
            if ($exist_admin) {
                return [
                    'code' => 1,
                    'msg' => '此用户名已被使用，请更换其它用户名',
                ];
            }
            $this->admin->username = $this->username;
            $this->admin->password = \Yii::$app->security->generatePasswordHash($this->password);
            $this->admin->auth_key = \Yii::$app->security->generateRandomString();
            $this->admin->access_token = \Yii::$app->security->generateRandomString();
            $this->admin->addtime = time();
        }
        if ($this->admin->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($this->admin);
        }
    }
}
