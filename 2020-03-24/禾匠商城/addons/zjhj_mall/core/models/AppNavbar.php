<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/11/21
 * Time: 17:57
 */

namespace app\models;

class AppNavbar
{
    /**
     * @return array
     */
    public static function getNavbar($store_id)
    {
        $default_navbar = [
            'background_image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX///+nxBvIAAAACklEQVQI12NgAAAAAgAB4iG8MwAAAABJRU5ErkJggg==',
            'border_color' => 'rgba(0,0,0,.1)',
            'navs' => [
                [
                    'url' => '/pages/index/index',
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/appnavbar/nav-icon-index.png',
                    'active_icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/appnavbar/nav-icon-index.active.png',
                    'text' => '商城',
                    'color' => '#888',
                    'active_color' => '#ff4544',
                ],
                [
                    'url' => '/pages/cat/cat',
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/appnavbar/nav-icon-cat.png',
                    'active_icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/appnavbar/nav-icon-cat.active.png',
                    'text' => '分类',
                    'color' => '#888',
                    'active_color' => '#ff4544',
                ],
                [
                    'url' => '/pages/cart/cart',
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/appnavbar/nav-icon-cart.png',
                    'active_icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/appnavbar/nav-icon-cart.active.png',
                    'text' => '购物车',
                    'color' => '#888',
                    'active_color' => '#ff4544',
                ],
                [
                    'url' => '/pages/user/user',
                    'icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/appnavbar/nav-icon-user.png',
                    'active_icon' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/images/appnavbar/nav-icon-user.active.png',
                    'text' => '我',
                    'color' => '#888',
                    'active_color' => '#ff4544',
                ],
            ],
        ];
        $navbar = Option::get('navbar', $store_id, 'app', json_encode($default_navbar, JSON_UNESCAPED_UNICODE));

        return json_decode($navbar, true);
    }

    public static function setNavbar($navbar, $store_id)
    {
        foreach ($navbar['navs'] as $index => $value) {
            if ($value['open_type'] == 'web') {
                $navbar['navs'][$index]['web'] = urlencode($value['web']);
            }
        }
        return Option::set('navbar', json_encode($navbar, JSON_UNESCAPED_UNICODE), $store_id, 'app');
    }
}
