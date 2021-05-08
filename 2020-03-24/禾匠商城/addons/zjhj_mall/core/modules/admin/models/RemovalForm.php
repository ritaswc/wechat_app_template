<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/3
 * Time: 18:52
 */

namespace app\modules\admin\models;

use app\models\Admin;
use app\models\Store;

class RemovalForm extends AdminModel
{
    public $store_id;
    public $user_id;

    public function rules()
    {
        return [
            [['store_id', 'user_id'], 'required'],
            [['store_id', 'user_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'store_id' => '商城',
            'user_id' => '账号'
        ];
    }

    public function save()
    {
        if ($this->validate() == false) {
            return $this->errorResponse;
        }

        $admin = Admin::findOne(['id' => $this->user_id]);
        if (!$admin) {
            return [
                'code' => 1,
                'msg' => '账户不存在'
            ];
        }

        $store = Store::findOne(['id' => $this->store_id]);

        if (!$store) {
            return [
                'code' => 1,
                'msg' => '商城不存在'
            ];
        }

        $storeCount = Store::find()->where([
            'admin_id' => $admin->id,
            'is_delete' => 0,
        ])->count();
        if ($storeCount >= $admin->app_max_count) {
            return [
                'code' => 1,
                'msg' => '该账户小程序商城数量已达到上限，无法迁移。',
            ];
        }

        $store->admin_id = $admin->id;
        if ($store->save()) {
            return [
                'code' => 0,
                'msg' => '迁移成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => $this->getErrorResponse($store)
            ];
        }
    }
}
