<?php

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\models\common\admin\log;

use app\models\ActionLog;
use app\models\Model;
use app\models\Option;
use app\models\User;
use app\modules\mch\controllers\permission\Menu;
use Yii;

class CommonActionLog
{
    public static function storeActionLog($title, $action, $isDelete, $result, $objId)
    {
        $storeId = Yii::$app->getSession()->get('store_id') ? Yii::$app->getSession()->get('store_id') : 0;
        //判断是否需要记录日志
        $option = Option::get(ActionLog::OPTION_NAME, $storeId, 'admin');

        if ($option == ActionLog::OPTION_SWITCH_OFF) {
            return false;
        }

        $type = isset(self::getAdmin()->identity->type) ? self::getAdmin()->identity->type : 'ims';

        if ($type == User::USER_TYPE_ROLE || $type == User::USER_TYPE_ADMIN || $type == 'ims') {
            $data = self::transformData($action, $isDelete, $title);
            $adminName = isset(self::getAdmin()->identity->nickname) ? self::getAdmin()->identity->nickname : self::getAdmin()->identity->username;

            $actionLog = new ActionLog();
            $actionLog->title = $data['title'];
            $actionLog->result = json_encode($result);
            $actionLog->addtime = time();
            $actionLog->admin_name = $adminName;
            $actionLog->admin_id = self::getAdmin()->id;
            $actionLog->admin_ip = Yii::$app->request->userIP;
            $actionLog->route = Yii::$app->controller->route;
            $actionLog->action_type = $data['action_type'];
            $actionLog->obj_id = $objId;
            $actionLog->store_id = $storeId ? $storeId : 0;
            $actionLog->is_delete = Model::IS_DELETE_FALSE;
            $actionLog->save();

            if (!$actionLog->save()) {
                return [
                    'code' => 1,
                    'msg' => '日志添加失败！'
                ];
            }
        }

        return true;
    }

    /**
     *
     *  商户小程序用户id修改
     */
    public static function storeMchLog($title, $action, $isDelete, $result, $objId)
    {
        $storeId = Yii::$app->getSession()->get('store_id') ? Yii::$app->getSession()->get('store_id') : 0;

        $data = self::transformData($action, $isDelete, $title);
        $adminName = isset(self::getAdmin()->identity->nickname) ? self::getAdmin()->identity->nickname : self::getAdmin()->identity->username;

        $actionLog = new ActionLog();
        $actionLog->title = $data['title'];
        $actionLog->result = Yii::$app->serializer->encode($result);
        $actionLog->addtime = time();
        $actionLog->admin_name = $adminName;
        $actionLog->admin_id = self::getAdmin()->id;
        $actionLog->admin_ip = Yii::$app->request->userIP;
        $actionLog->route = Yii::$app->controller->route;
        $actionLog->action_type = $data['action_type'];
        $actionLog->obj_id = $objId;
        $actionLog->store_id = $storeId ? $storeId : 0;
        $actionLog->is_delete = Model::IS_DELETE_FALSE;
        $actionLog->save();

        if (!$actionLog->save()) {
            return [
                'code' => 1,
                'msg' => '日志添加失败！'
            ];
        }
        return true;
    }

    public static function getAdmin()
    {
        if (Yii::$app->mchRoleAdmin->isGuest == false) {
            return Yii::$app->mchRoleAdmin;
        }

        if (Yii::$app->user->isGuest == false) {
            return Yii::$app->user;
        }

        if (Yii::$app->admin->isGuest == false) {
            return Yii::$app->admin;
        }
    }

    public static function transformData($action, $isDelete, $title = '')
    {
        $adminName = isset(self::getAdmin()->identity->nickname) ? self::getAdmin()->identity->nickname : self::getAdmin()->identity->username;

        if ($action === 'login') {
            return [
                'action_type' => 'LOGIN',
                'title' => $adminName . '登录了系统'
            ];
        }

        //如果$title没有传值,默认获取当前路由名称记录
        $currentRoute = $title ? $title : Yii::$app->controller->route;

        if (empty($title)) {
            $route = Yii::$app->getCache()->get('action_log_route');
            if ($route == false) {
                $menu = Menu::getMenu();
                $route = self::getRoute($menu);
                Yii::$app->getCache()->set('action_log_route', $route, 1800);
            }

            foreach ($route as $item) {
                if ($item['route'] == $currentRoute) {
                    $currentRoute = $item['name'];
                    break;
                }
            }
        }

        if ($isDelete == Model::IS_DELETE_TRUE) {
            return [
                'action_type' => 'DESTROY',
                'title' => $adminName . '执行了' . $currentRoute
            ];
        }

        if ($action == true) {
            return [
                'action_type' => 'INSERT',
                'title' => $adminName . '执行了' . $currentRoute . '添加'
            ];
        }

        if ($action == false) {
            return [
                'action_type' => 'UPDATE',
                'title' => $adminName . '执行了' . $currentRoute . '更新'
            ];
        }
    }

    /**
     * 获取所有route 及 name
     * @param $menu
     * @param int $id
     * @return array
     */
    public static function getRoute($menu, $id = 0)
    {
        $arr = [];
        foreach ($menu as $k => $item) {
            $arr[$id]['route'] = $item['route'];
            $arr[$id]['name'] = $item['name'];
            $id++;

            if (isset($item['children']) && is_array($item['children'])) {
                $arr = array_merge($arr, self::getRoute($item['children'], $id));
            }

            if (isset($item['action']) && is_array($item['action'])) {
                $arr = array_merge($arr, self::getRoute($item['action'], $id));
            }

            if (isset($item['sub']) && is_array($item['sub'])) {
                $arr = array_merge($arr, self::getRoute($item['sub'], $id));
            }
        }

        return $arr;
    }
}
