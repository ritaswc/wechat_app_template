<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/27
 * Time: 10:44
 */

namespace app\modules\mch\models;

use app\models\Option;
use app\modules\mch\controllers\permission\PermissionsMenu;
use app\modules\mch\models\permission\permission\IndexPermissionForm;
use Yii;

class MchMenu
{
    /**
     * 判断版本：we7(微擎版) | ind(独立版)
     */
    public $platform;
    /**
     * 账户权限
     */
    public $user_auth;
    /**
     * 是否为总账户
     */
    public $is_admin;
    /**
     * 线个版本
     */
    public $offline;

    public function getList()
    {

        $model = new IndexPermissionForm();
        $permissions = $model->getPermissionByUser();

        $menuList = [];
        $permissionsMenu = new PermissionsMenu();
        $list = $permissionsMenu->getPermissionMenu();

        $menuList = array_merge($menuList, $this->getMenuList($list, $permissions));
        $menuList = $this->resetList($menuList);
        $menuList = $this->deleteEmptyList($menuList);

        return $menuList;
    }

    public function resetList($list)
    {
        foreach ($list as $i => $item) {
            if ($item['name'] == '教程管理') {
                $a = Option::get('handle', 0, 'admin');
                if ($a) {
                    $arr = json_decode($a, true);
                    if ($arr['status'] == 0) {
                        $list[$i]['admin'] = true;
                        $item['admin'] = true;
                    } else {
                        $list[$i]['admin'] = false;
                        $item['admin'] = false;
                    }
                } else {
                    $list[$i]['admin'] = true;
                    $item['admin'] = true;
                }
            }
            //如果不是总账号、则去除admin = true的菜单
            if (isset($item['admin']) && $item['admin'] && !$this->is_admin) {
                unset($list[$i]);
                continue;
            }
            //独立版某些菜单在外面显示，通过is_ind判断
            if (isset($item['is_ind']) && $item['is_ind'] && Yii::$app->controller->is_ind) {
                unset($list[$i]);
                continue;
            }
            if (isset($item['we7']) && $item['we7'] && $this->platform != 'we7') {
                unset($list[$i]);
                continue;
            }
            if (isset($item['key']) && $this->user_auth !== null && !in_array($item['key'], $this->user_auth)) {
                unset($list[$i]);
                continue;
            }

            if (isset($item['offline']) && isset($this->offline) && $this->offline !== true) {
                unset($list[$i]);
                continue;
            }
            if (isset($item['is_show']) && $item['is_show'] !== true) {
                unset($list[$i]);
                continue;
            }

            if (isset($item['children']) && is_array($item['children'])) {
                $list[$i]['children'] = $this->resetList($item['children']);
            }
        }
        $list = array_values($list);
        return $list;
    }

    /**
     * 获取动态生成的菜单
     * @param $permissions
     * @param $arr
     * @return array
     */
    public function getMenuList($permissions, $arr)
    {
        foreach ($permissions as $k => $item) {
            //微擎账号和独立版账号
            if (Yii::$app->user->isGuest == false || Yii::$app->admin->isGuest == false) {
                $permissions[$k]['is_show'] = true;
            } else {
                if (in_array($item['route'], $arr) && $item['route']) {
                    $permissions[$k]['is_show'] = true;
                } else {
                    $permissions[$k]['is_show'] = false;
                }
            }

            if (isset($item['children'])) {
                $permissions[$k]['children'] = $this->getMenuList($item['children'], $arr);
                foreach ($permissions[$k]['children'] as $i) {
                    if ($i['is_show'] == true) {
                        $permissions[$k]['route'] = $i['route'];
                        $permissions[$k]['is_show'] = true;
                        break;
                    }
                }
            }

            if (isset($item['sub'])) {
                $permissions[$k]['sub'] = $this->getMenuList($item['sub'], $arr);
            }
        }

        return $permissions;
    }

    /**
     * 当父数组下没有没有一个子元素时，因将该父数组删除
     * @param $menuList
     * @return array
     */
    public function deleteEmptyList($menuList)
    {
        foreach ($menuList as $i => $item) {
            if (is_array($item['children']) && count($item['children']) == 0) {
                unset($menuList[$i]);
                continue;
            }
            if (is_array($item['children'])) {
                $menuList[$i]['route'] = $item['children'][0]['route'];
            }
        }
        $menuList = array_values($menuList);

        return $menuList;
    }
}
