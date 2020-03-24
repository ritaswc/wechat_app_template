<?php

define('IN_SYS', true);

require '../framework/bootstrap.inc.php';

define('dianc_ROOT', dirname(dirname(__FILE__)));
define('IS_OPERATOR', true);
require dianc_ROOT . '/addons/lywywl_ztb/admin/common/bootstrap.sys.inc.php';
require dianc_ROOT . '/addons/lywywl_ztb/admin/common/template.func.php';
require dianc_ROOT . '/addons/lywywl_ztb/admin/common/common.func.php';
$urls = parse_url($_W['siteroot']);
$arr = explode('/', $urls['path']);

$path .= '/';
$urls['path'] = $path;
$_W['siteroot'] = $urls['scheme'].'://'.$urls['host'].((!empty($urls['port']) && $urls['port']!='80') ? ':'.$urls['port'] : '').$urls['path'];

if (empty($_W['isfounder']) && !empty($_W['user']) && $_W['user']['status'] == 1) {
    message('您的账号正在审核或是已经被系统禁止，请联系网站管理员解决！');
}
$acl = array(
    'user' => array(
        'default' => 'login',
        'direct' => array(
            'login',
            'register',
            'logout',
        ),
    ),
    'utility' => array(
        'default' => '',
        'direct' => array(
            'verifycode',
            'code',
            'file',
            'bindcall',
            'subscribe',
            'wxcode',
            'modules',
            'link',
        ),
        'vice_founder' => array(
            'user',
            'emulator',
        ),
        'owner' => array(
            'emulator',
        ),
        'manager' => array(
            'emulator',
        ),
        'operator' => array(
            'emulator',
        ),
        'unbind_user' => array(),
    ),
);

if (($_W['setting']['copyright']['status'] == 1) && empty($_W['isfounder']) && $controller != 'cloud' && $controller != 'utility' && $controller != 'account') {
    $_W['siteclose'] = true;
    if ($controller == 'account' && $action == 'welcome') {
        template('account/welcome');
        exit;
    }
    if ($controller == 'user' && $action == 'login') {
        if (checksubmit()) {
            require _forward($controller, $action);
        }
        template('user/login');
        exit;
    }
    isetcookie('__session', '', -10000);
    message('站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason'], url('account/welcome'), 'info');
}

$controllers = array();
$handle = opendir(IA_ROOT . '/web/source/');
if(!empty($handle)) {
    while($dir = readdir($handle)) {
        if($dir != '.' && $dir != '..') {
            $controllers[] = $dir;
        }
    }
}
if(!in_array($controller, $controllers)) {
    $controller = 'account';
}


$init = IA_ROOT . "/web/source/{$controller}/__init.php";
if(is_file($init)) {
    require $init;
}

$actions = array();
$handle = opendir(IA_ROOT . '/web/source/' . $controller);
if(!empty($handle)) {
    while($dir = readdir($handle)) {
        if($dir != '.' && $dir != '..' && strexists($dir, '.ctrl.php')) {
            $dir = str_replace('.ctrl.php', '', $dir);
            $actions[] = $dir;
        }
    }
}
if(empty($actions)) {
    header('location: ?refresh');
}
if(!in_array($action, $actions)) {
    $action = $acl[$controller]['default'];
}
if(!in_array($action, $actions)) {
    $action = $actions[0];
}

if (is_array($acl[$controller]['direct']) && in_array($action, $acl[$controller]['direct'])) {
    require _forward($controller, $action);
    exit();
}

$_W['page'] = array();
$_W['page']['copyright'] = $_W['setting']['copyright'];

//进入登入页面
if(is_array($acl[$controller]['direct']) && in_array($action, $acl[$controller]['direct'])) {

    require _forward($controller, $action);
    exit;
}

if(is_array($acl[$controller]['founder']) && in_array($action, $acl[$controller]['founder'])) {
    if(!$_W['isfounder']) {
        message('不能访问, 需要创始人权限才能访问.');
    }
}

checklogin();
if(!defined('IN_GW')) {
    if(!in_array($_W['role'], array('manager', 'operator', 'founder', 'clerk'))) {
    }
}

require _forward($controller, $action);

define('ENDTIME', microtime());
if (empty($_W['config']['setting']['maxtimeurl'])) {
    $_W['config']['setting']['maxtimeurl'] = 10;
}
if ((ENDTIME - STARTTIME) > $_W['config']['setting']['maxtimeurl']) {
    $data = array(
        'type' => '1',
        'runtime' => ENDTIME - STARTTIME,
        'runurl' => $_W['sitescheme'].$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
        'createtime' => TIMESTAMP
    );
    pdo_insert('core_performance', $data);
}

//判断进去
function _forward($c, $a) {
    $file = dianc_ROOT . '/addons/lywywl_ztb/admin/source/' . $c . '/' . $a . '.ctrl.php';
    if (!file_exists($file)) {
        //todo:修改的地方
        header('location:' . url('site/entry/home', array('m' => 'lywywl_ztb')));
        exit();
    }
    return $file;
}
function _calc_current_frames(&$frames) {
    global $controller, $action;
    if(!empty($frames) && is_array($frames)) {
        foreach($frames as &$frame) {
            if(empty($frame['items'])) continue;
            foreach($frame['items'] as &$fr) {
                $query = parse_url($fr['url'], PHP_URL_QUERY);
                parse_str($query, $urls);
                if(empty($urls)) continue;
                if(defined('ACTIVE_FRAME_URL')) {
                    $query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
                    parse_str($query, $get);
                } else {
                    $get = $_GET;
                    $get['c'] = $controller;
                    $get['a'] = $action;
                }
                if(!empty($do)) {
                    $get['do'] = $do;
                }

                $diff = array_diff_assoc($urls, $get);
                if(empty($diff)) {
                    $fr['active'] = ' active';
                }
            }
        }
    }
}