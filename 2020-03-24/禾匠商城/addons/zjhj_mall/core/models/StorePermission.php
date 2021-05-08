<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/6/19
 * Time: 10:26
 */


namespace app\models;


use yii\base\BaseObject;

class StorePermission extends BaseObject
{

    /**
     * 获取当前小程序商城的权限（固定权限、已安装插件）
     * @param int we7Uid 该参数只用于后端管理接口(除操作员，操作员和小程序请求类似)
     * @return array ['coupon','miaosha',...]
     */
    public static function getOpenPermissionList($store = null, $we7Uid = 0)
    {
        if (!$store) {
            return [];
        }
        if ($store->user_id) {
            //微擎版账户
            $user = User::findOne($store->user_id);
            if (!$user || !$user->we7_uid) {
                return null;
            }

            $we7Uid = $we7Uid ? $we7Uid : $user->we7_uid;
            $we7UserAuth = We7UserAuth::findOne(['we7_user_id' => $we7Uid]);
            if (!$we7UserAuth) {
                //微擎账户未设置过权限，是否默认有所有权限，总管理员默认有所有权限
                $we7_default_all_permission = Option::get('we7_default_all_permission');
                if ($we7_default_all_permission || $we7Uid == 1) {
                    $permission_list = self::getAllPermissionList();
                } else {
                    $permission_list = [];
                }
            } else {
                //微擎账户设置过权限，根据已设置的权限
                if ($we7UserAuth->auth) {
                    $permission_list = \Yii::$app->serializer->decode($we7UserAuth->auth);
                    if (!$permission_list) {
                        $permission_list = [];
                    }
                } else {
                    $permission_list = [];
                }
            }
            if ($we7Uid == 1) {
                $permission_list = self::getAllPermissionList();
            }
        } elseif ($store->admin_id) {

            //独立版账户
            $adminId = $we7Uid ? $we7Uid : $store->admin_id;
            $admin = Admin::findOne($adminId);
            if (!$admin) {
                return null;
            }
            if ($admin->permission) {
                $permission_list = \Yii::$app->serializer->decode($admin->permission);
                if (!$permission_list) {
                    $permission_list = [];
                }
            } else {
                $permission_list = [];
            }
        } else {
            return [];
        }

        return (array)$permission_list;

    }

    /**
     * 获取当前系统所有权限（固定权限、已安装插件）
     * @return array ['coupon','miaosha',...]
     */
    private static function getAllPermissionList()
    {
        $plugin_list = \app\hejiang\cloud\CloudPlugin::getInstallPluginList();
        $plugin_permission_list = [];
        if (is_array($plugin_list)) {
            foreach ($plugin_list as $p) {
                $plugin_permission_list[] = $p['name'];
            }
        }
        $admin_permission_list = AdminPermission::getList();
        $system_permission_list = [];
        if (is_array($admin_permission_list)) {
            foreach ($admin_permission_list as $ap) {
                $system_permission_list[] = $ap->name;
            }
        }
        return array_keys(array_flip($plugin_permission_list) + array_flip($system_permission_list));
    }
}
