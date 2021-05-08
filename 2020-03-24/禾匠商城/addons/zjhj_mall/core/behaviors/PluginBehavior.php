<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/9/10 10:13
 */


namespace app\behaviors;


use app\hejiang\Cloud;
use app\hejiang\CloudPlugin;

class PluginBehavior extends BaseBehavior
{
    public $only_routes = [
        '*'
    ];

    public function beforeAction($event)
    {
        $plugin_route_maps = [
            'miaosha' => 'mch/miaosha/*',
            'pintuan' => 'mch/group/*',
            'book' => 'mch/book/*',
            'fxhb' => 'mch/fxhb/*',
            'mch' => 'mch/mch/*',
            'pond' => 'mch/pond/*',
            'bargain' => 'mch/bargain/*',
            'scratch' => 'mch/scratch/*',
            'integralmall' => 'mch/integralmall/*',
            'permission' => 'mch/permission/role/*',
            'alipay' => 'mch/alipay/*',
        ];
        foreach ($plugin_route_maps as $name => $route) {
            if (self::matchRoutes([$route])) {
                $res = CloudPlugin::check($name);
                if (isset($res['code']) && isset($res['data']['status']) && $res['data']['status'] != 0) {
                    echo \Yii::$app->view->render('//error/auth', [
                        'msg' => $res['data']['content'],
                    ]);
                    \Yii::$app->end();
                }
            }
        }
    }

}
