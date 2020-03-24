<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/7/11
 * Time: 16:57
 */

namespace app\plugins\bargain\init;


use app\plugins\bargain\events\AddGoodsEvent;

class Controller
{
    public static function init($module = null)
    {
        // 商品管理
        \Yii::$app->eventDispatcher->mount(new AddGoodsEvent());
        // 虚拟控制器=>真实控制器
        $map = [
            'bargain/goods' => 'goods',
            'bargain/order' => 'order',
            'lottery/order' => 'order',
            'step/order' => 'order',
            'step/goods' => 'goods',
        ];
        array_walk($map, [$module, 'redirectController']);
    }
}