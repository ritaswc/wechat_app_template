<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/6
 * Time: 13:59
 */

namespace app\modules\admin\models;

use app\models\Admin;
use app\models\Store;
use app\models\WechatApp;

class AppEditForm extends AdminModel
{
    public $name;
    public $admin_id;

    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name'], 'required'],
            [['name'], 'string', 'length' => [0, 200]],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $admin = Admin::findOne($this->admin_id);
        $store_count = Store::find()->where([
            'admin_id' => $this->admin_id,
            'is_delete' => 0,
        ])->count();
        if ($store_count && $admin->app_max_count && $store_count >= $admin->app_max_count) {
            return [
                'code' => 1,
                'msg' => '小程序创建数量超过上限',
            ];
        }

        $wechat_app = new WechatApp();
        $wechat_app->acid = 0;
        $wechat_app->user_id = 0;
        $wechat_app->name = $this->name;
        $wechat_app->app_id = '0';
        $wechat_app->app_secret = '0';
        if (!$wechat_app->save()) {
            return $this->getErrorResponse($wechat_app);
        }


        $store = new Store();
        $store->admin_id = $this->admin_id;
        $store->is_delete = 0;
        $store->name = $this->name;
        $store->acid = 0;
        $store->user_id = 0;
        $store->wechat_app_id = $wechat_app->id;
        if ($store->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        }
        return $this->getErrorResponse($store);
    }
}
