<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\admin\models;

use Yii;

class AdminMenu
{
    //子账号显示的菜单路由
    public $route = [
        'admin/user/me',
        'admin/app/index',
        'admin/app/recycle',
        'admin/cache/index',
    ];

    public function getMenu()
    {
        $data = Yii::$app->getCache()->get($this->getMenuCacheKey());
        if ($data) {
            return $data;
        }

        $menu = Menu::getMenu();
        $menuList = $this->resetList($menu, $this->route);
        $menuList = $this->delete($menuList);


        Yii::$app->getCache()->set($this->getMenuCacheKey(), $menuList, 1800);
        return $menuList;
    }

    public function resetList($list, $route)
    {
        foreach ($list as $k => $item) {
            if (Yii::$app->admin->id == 1) {
                $list[$k]['show'] = true;
            } else {
                if (in_array($item['route'], $route)) {
                    $list[$k]['show'] = true;
                } else {
                    $list[$k]['show'] = false;
                }
            }


            if (isset($item['children']) && is_array($item['children'])) {
                $list[$k]['children'] = $this->resetList($item['children'], $route);
                foreach ($list[$k]['children'] as $i) {
                    if ($i['show']) {
                        $list[$k]['route'] = $i['route'];
                        $list[$k]['show'] = true;
                        break;
                    }
                }
            }
        }

        return $list;
    }


    public function delete($menuList)
    {
        foreach ($menuList as $k1 => $item) {
            if (isset($item['children'])) {
                $menuList[$k1]['children'] = $this->delete($item['children']);
            }

            if ($item['show'] == false) {
                unset($menuList[$k1]);
            }
        }

        return $menuList;
    }

    /**
     * 现只用于左侧菜单缓存
     * @return string
     */
    public function getMenuCacheKey()
    {
        //用户accessToken 作为用户菜单的唯一标识符
        $adminId = Yii::$app->admin->id;
        $accessToken = Yii::$app->admin->identity->access_token;
        $cacheKey = 'ind-' . $adminId . $accessToken;

        return $cacheKey;
    }
}
