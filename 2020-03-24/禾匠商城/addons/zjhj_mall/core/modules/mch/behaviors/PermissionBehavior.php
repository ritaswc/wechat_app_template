<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\behaviors;

use app\modules\mch\controllers\permission\Menu;
use app\modules\mch\controllers\permission\PermissionsMenu;
use app\modules\mch\models\permission\permission\IndexPermissionForm;
use yii\base\ActionFilter;
use Yii;

class PermissionBehavior extends ActionFilter
{
    /**
     * 总管理员
     */
    const SUPER_ADMIN_ID = 1;

    /**
     * 安全路由，权限验证时会排除这些路由
     * @var array
     */
    private $safeRoute = [
        'mch/default/index',
        'mch/store/index',
        'mch/alipay/download'
    ];

    public function beforeAction($action)
    {
        //路由名称
        $route = Yii::$app->requestedRoute;
        if (Yii::$app->request->isAjax) {
            return true;
        }

        //排除安全路由
        if (in_array($route, $this->safeRoute)) {
            return true;
        }

        $menu = Menu::getMenu();
        //微擎和独立版账号不需要进行验证
        if (Yii::$app->controller->is_we7 || Yii::$app->controller->is_ind) {
            if ($this->getAdminId() == self::SUPER_ADMIN_ID) {
                return true;
            }

            //判断子账号权限
            $permissions = $this->getChildAdminPermissions($menu);
            if (in_array($route, $permissions)) {
                return true;
            }
            $this->permissionError();
        }

        //判断操作员权限
        $model = new IndexPermissionForm();
        $userPermissions = $model->getPermissionByUser();

        $permissions = $this->getUserPermissions($menu, $userPermissions);

        if (!in_array($route, $permissions)) {
            $this->permissionError();
        }

        return true;
    }

    /**
     * 获取角色所拥有的权限
     * @param $menu
     * @param $pList
     * @return array
     */
    public function getUserPermissions($menu, $pList)
    {
        $arr = [];
        foreach ($menu as $k => $item) {
            //TODO in_array() item['route']为空字符串，也是true
            if (in_array($item['route'], $pList) || isset($item['children'])) {

                if (isset($item['children']) && is_array($item['children'])) {
                    $arr = array_merge($arr, $this->getUserPermissions($item['children'], $pList));
                } else {
                    $arr[] = $item['route'];
                }

                if (isset($item['sub']) && is_array($item['sub'])) {
                    foreach ($item['sub'] as $i) {
                        $arr[] = $i['route'];
                    }
                }
            }
        }

        return $arr;
    }

    /**
     * 获取子账号拥有的权限
     * @param $menu
     * @param $pList
     * @return array
     */
    public function getChildAdminPermissions($menu)
    {
        $arr = [];
        foreach ($menu as $k => $item) {
            if (isset($item['admin']) == false) {
                $arr[] = $item['route'];
            }

            if (isset($item['children']) && is_array($item['children'])) {
                $arr = array_merge($arr, $this->getChildAdminPermissions($item['children']));
            }

            if (isset($item['sub']) && is_array($item['sub'])) {
                $arr = array_merge($arr, $this->getChildAdminPermissions($item['sub']));
            }
        }

        return $arr;
    }

    public function getAdminId()
    {
        if (Yii::$app->user->isGuest == false) {
            return Yii::$app->user->identity->we7_uid;
        }

        if (Yii::$app->admin->isGuest == false) {
            return Yii::$app->admin->id;
        }
    }

    public function permissionError()
    {
        $url = Yii::$app->urlManager->createUrl('mch/error/permission-error');
        Yii::$app->response->redirect($url)->send();
    }
}
