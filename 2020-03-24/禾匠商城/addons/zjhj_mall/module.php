<?php
defined('IN_IA') or exit('Access Denied');

error_reporting(E_ALL & ~E_NOTICE);

function hj_entry()
{
    $entry = '/core/web/index.php';
    if (file_exists(__DIR__ . $entry)) {
        global $_W;
        $wUser = [
            'uid' => $_W['user']['uid'],
            'name' => $_W['user']['name'],
            'username' => $_W['user']['username'],
        ];
        $wAccount = [
            'acid' => $_W['account']['acid'],
            'name' => $_W['account']['name'],
        ];

        require __DIR__ . '/core/vendor/autoload.php';
        $app = new app\hejiang\Application();
        $app->session->remove('__mchRoleAdmin_id');
        $app->session->set('we7_user', $wUser);
        $app->session->set('we7_account', $wAccount);
        $rp = $app->urlManager->routeParam;

        $uri = $_W['siteroot'] . 'addons/' . $_W['current_module']['name'] . $entry . '?' . $rp . '=mch/passport/login';
        header('Location: ' . $uri);
        exit;
    } else {
        die('应用入口文件缺失，请联系开发者处理！');
    }
}

class Zjhj_mallModule extends WeModule
{
    public function welcomeDisplay()
    {
        hj_entry();
    }
}
