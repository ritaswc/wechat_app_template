<?php

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/8
 * Time: 18:22
 */

namespace app\modules\admin\behaviors;

use app\modules\admin\models\Permissions;
use yii\base\ActionFilter;

class AdminBehavior extends ActionFilter
{
    /**
     * 安全路由、不需要验证
     * @var array
     */
    public $safes = [
        'admin/default/index',
        'admin/passport/login',
        'admin/passport/logout',
        'admin/passport/send-sms-code',
        'admin/passport/reset-password',
        'admin/default/alter-password',
    ];

    /**
     * @param \yii\base\Action $e
     * @return bool
     */
    public function beforeAction($e)
    {
        $route = \Yii::$app->controller->route;
        if (in_array($route, $this->safes)) {
            return true;
        }

        if (\Yii::$app->admin->isGuest) {
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->data = [
                    'code' => -1,
                    'msg' => '请先登录'
                ];
            } else {
                \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl('admin/passport/login'))->send();
            }
            return false;
        }

        if (\Yii::$app->admin->id == 1) {
            $isAdmin = \Yii::$app->session->set('__is_admin', true);
            return true;
        }

        $permissions = Permissions::getCAdminPermission();
        if (in_array($route, $permissions)) {
            $isAdmin = \Yii::$app->session->set('__is_admin', true);
            return true;
        }
        $isAdmin = \Yii::$app->session->set('__is_admin', false);

        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->data = [
                'code' => 1,
                'msg' => '您不是超级管理员，无操作权限',
            ];
            return false;
        } else {
            \Yii::$app->response->redirect(\Yii::$app->urlManager->createUrl([
                'admin/default/index',
            ]))->send();
            return false;
        }
    }
}
