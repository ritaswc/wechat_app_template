<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\permission\permission;

use app\models\AuthRolePermission;
use app\models\User;
use app\modules\mch\models\MchMenu;
use app\modules\mch\models\MchModel;
use Yii;

class IndexPermissionForm extends MchModel
{

    public function getList()
    {
        $menuList = \Yii::$app->controller->getMenuList();
        $newMenuList = $this->deleteAdminMenu($menuList);
        return $newMenuList;
    }

    /**
     * 获取当前登录用户所拥有的权限Route
     * @return array
     */
    public function getPermissionByUser()
    {
        $roles = [];
        //只有角色登录才去查权限列表
        if (!Yii::$app->mchRoleAdmin->isGuest) {
            $user = User::find()->where(['id' => $this->getCurrentUserId()])->with('roleUser')->one();

            foreach ($user->roleUser as $item) {
                $roles[] = $item->role->id;
            }
        }
        $permissions = AuthRolePermission::find()->where(['in', 'role_id', $roles])->all();

        $data = [];
        foreach ($permissions as $permission) {
            $data[] = $permission->permission_name;
        }
        return $data;
    }

    /**
     * 获取编辑时的权限列表
     */
    public function getPermissionMenuByUser($roleId)
    {
        $list = $this->getList();
        $permissions = AuthRolePermission::find()->where(['role_id' => $roleId])->all();

        $data = [];
        foreach ($permissions as $permission) {
            $data[] = $permission->permission_name;
        }
        $model = new MchMenu();
        $newList = $model->deleteEmptyList($list);

        $resetList = $this->resetPermissionMenu($newList, $data);
        
        $permissionsMenu = Yii::$app->serializer->encode($resetList);

        return $permissionsMenu;
    }

    /**
     * 给用户已有的权限加上show字段标识
     * @param $list
     * @param $permissions
     * @return mixed
     */
    public function resetPermissionMenu($list, $permissions)
    {
        foreach ($list as $key => $item) {
            if (in_array($item['route'], $permissions)) {
                $list[$key]['show'] = true;
            }
            if (isset($item['children'])) {
                //一级和二级菜单编辑时要设置为空，不然更新是会有bug
                $list[$key]['route'] = '';
                $list[$key]['children'] = $this->resetPermissionMenu($item['children'], $permissions);
            }
        }

        return $list;
    }

    /**
     * 去除总管理员独有的菜单，这些菜单子账号和操作员都不能使用
     * @param $list
     * @return mixed
     */
    public function deleteAdminMenu($list)
    {
        foreach ($list as $k => $item) {

            if ($item['admin'] === true) {
                unset($list[$k]);
                continue;
            }
            $removePermissions = ['permission'];
            if (isset($item['key']) && in_array($item['key'], $removePermissions)) {
                unset($list[$k]);
                continue;
            }

            if (isset($item['children']) && count($item['children']) > 0) {
                $list[$k]['children'] = $this->deleteAdminMenu($item['children']);
            }
        }
        $list = array_values($list);
        return $list;
    }
}
