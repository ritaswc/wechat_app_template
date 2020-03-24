<?php

namespace app\hejiang;

use app\models\Admin;
use app\models\AdminPermission;
use app\modules\admin\models\UserEditForm;
use yii\helpers\VarDumper;

class CloudAdmin
{
    public static function getEditUserData()
    {
        $admin = Admin::findOne([
            'id' => \Yii::$app->request->get('id'),
            'is_delete' => 0,
        ]);
        if (!$admin) {
            $admin = new Admin();
        }
        $auth_info = Cloud::getAuthInfo();
        $account_count = (Admin::find()->where(['is_delete' => 0])->count()) - 1;
        $account_max = -1;
        $account_over_max = $admin->isNewRecord && $account_max != -1 && $account_count >= $account_max;
        return [
            'model' => $admin,
            'account_count' => $account_count,
            'account_max' => $account_max,
            'account_over_max' => $account_over_max,
            'permission_list' => AdminPermission::getList(),
        ];
    }

    public static function saveEditUserData()
    {
        $form = new UserEditForm();
        $admin = Admin::findOne([
            'id' => \Yii::$app->request->get('id'),
            'is_delete' => 0,
        ]);
        if (!$admin) {
            $admin = new Admin();
        }
        if ($admin->isNewRecord) {
            $form->scenario = 'add';
        }


        $auth_info = Cloud::getAuthInfo();
        $account_count = (Admin::find()->where(['is_delete' => 0])->count()) - 1;
        $account_max = -1;
        $account_over_max = $admin->isNewRecord && $account_max != -1 && $account_count >= $account_max;
        if ($account_over_max) {
            return [
                'code' => 1,
                'msg' => '保存失败，子账户创建数量上限',
            ];
        }

        $form->attributes = \Yii::$app->request->post();
        $form->admin = $admin;
        return $form->save();
    }
}
