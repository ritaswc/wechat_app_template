<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/15
 * Time: 15:18
 */

namespace app\models;

class UserCenterMenu extends Model
{
    public $store_id;

    private function getDefaultMenuList()
    {
        $store = Store::findOne($this->store_id);
        $default_menu_list = [
            [
                'is_show' => 1,
                'name' => '我的拼团',
                'icon' => '/images/pt-my-group.png',
                'open_type' => 'navigator',
                'url' => '/pages/pt/order/order',
                'tel' => '',
            ],
            [
                'is_show' => 1,
                'name' => '分销中心',
                'icon' => '/images/icon-user-fx.png',
                'open_type' => 'navigator',
                'url' => '/pages/share/index',
                'tel' => '',
            ],
            [
                'is_show' => 1,
                'name' => '我的卡券',
                'icon' => '/images/icon-user-card.png',
                'open_type' => 'navigator',
                'url' => '/pages/card/card',
                'tel' => '',
            ],
            [
                'is_show' => 1,
                'name' => '我的优惠券',
                'icon' => '/images/icon-user-yhq.png',
                'open_type' => 'navigator',
                'url' => '/pages/coupon/coupon',
                'tel' => '',
            ],
            [
                'is_show' => 1,
                'name' => '领券中心',
                'icon' => '/images/icon-user-lingqu.png',
                'open_type' => 'navigator',
                'url' => '/pages/coupon-list/coupon-list',
                'tel' => '',
            ],
            [
                'is_show' => 1,
                'name' => '我的收藏',
                'icon' => '/images/icon-user-sc.png',
                'open_type' => 'navigator',
                'url' => '/pages/favorite/favorite',
                'tel' => '',
            ],
            [
                'is_show' => 1,
                'name' => '在线客服',
                'icon' => '/images/icon-user-kf.png',
                'open_type' => 'contact',
                'url' => '',
                'tel' => '',
            ],
            [
                'is_show' => 1,
                'name' => '联系我们',
                'icon' => '/images/icon-user-lx.png',
                'open_type' => 'tel',
                'url' => '',
                'tel' => $store ? $store->contact_tel : '',
            ],
            [
                'is_show' => 1,
                'name' => '服务中心',
                'icon' => '/images/icon-help.png',
                'open_type' => 'navigator',
                'url' => '/pages/article-list/article-list?id=2',
                'tel' => '',
            ],
            [
                'is_show' => 1,
                'name' => '关于我们',
                'icon' => '/images/icon-about-us.png',
                'open_type' => 'navigator',
                'url' => '/pages/article-detail/article-detail?id=about_us',
                'tel' => '',
            ],
            [
                'is_show' => 1,
                'name' => '我的预约',
                'icon' => '/images/icon-about-us.png',
                'open_type' => 'navigator',
                'url' => '/pages/book/order/order',
                'tel' => '',
            ],
        ];
        return $default_menu_list;
    }

    public function saveMenuList($menu_list)
    {
        $res = Option::set('user_center_menu_list', $menu_list, $this->store_id, 'app');
        if ($res) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '保存失败',
            ];
        }
    }

    public function getMenuList()
    {
        $store = Store::findOne($this->store_id);
        $data = Option::get('user_center_menu_list', $this->store_id, 'app');
        $default_list = self::getDefaultMenuList();
        if (!$data) {
            $list = $default_list;
        } else {
            foreach ($data as $i => $exist_item) {
                foreach ($default_list as $j => $item) {
                    if (!isset($data['item_' . $j])) {
                        $data['item_' . $j] = $item;
                    }
                }
            }
            $list = $data;
        }
        foreach ($list as $i => $item) {
            if ($item['name'] == '联系我们') {
                $list[$i]['tel'] = $store ? $store->contact_tel : '';
            }
        }
        return $list;
    }
}
