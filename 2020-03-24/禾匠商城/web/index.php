<?php
/**
 * [WeEngine System] Copyright (c) 20191214152815 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_SYS', true);
require '../framework/bootstrap.inc.php';
require IA_ROOT . '/web/common/bootstrap.sys.inc.php';

if (!empty($_GPC['state'])) {
	$login_callback_params = OAuth2Client::supportParams($_GPC['state']);
	if (!empty($login_callback_params)) {
		$controller = 'user';
		$action = 'login';
		$_GPC['login_type'] = $login_callback_params['from'];
		$_GPC['handle_type'] = $login_callback_params['mode'];
	}
}

if (empty($_W['isfounder']) && !empty($_W['user']) && ($_W['user']['status'] == USER_STATUS_CHECK || $_W['user']['status'] == USER_STATUS_BAN)) {
	isetcookie('__session', '', -10000);
	message('您的账号正在审核或是已经被系统禁止，请联系网站管理员解决！', url('user/login'), 'info');
}
$acl = require IA_ROOT . '/web/common/permission.inc.php';

$_W['page'] = array();
$_W['page']['copyright'] = $_W['setting']['copyright'];

if (($_W['setting']['copyright']['status'] == 1) && empty($_W['isfounder']) && 'cloud' != $controller && 'utility' != $controller && 'account' != $controller) {
	$_W['siteclose'] = true;
	if ('account' == $controller && 'welcome' == $action) {
		template('account/welcome');
		exit();
	}
	if ('user' == $controller && 'login' == $action) {
		if (checksubmit() || $_W['isajax'] && $_W['ispost']) {
			require _forward($controller, $action);
		}
		template('user/login');
		exit();
	}
	isetcookie('__session', '', -10000);
	if ($_W['isajax']) {
		iajax(-1, '站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason']);
	}
	
	
		message('站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason'], url('account/welcome'), 'info');
	
}

$controllers = array();
$handle = opendir(IA_ROOT . '/web/source/');
if (!empty($handle)) {
	while ($dir = readdir($handle)) {
		if ('.' != $dir && '..' != $dir) {
			$controllers[] = $dir;
		}
	}
}
if (!in_array($controller, $controllers)) {
	$controller = 'home';
}

$init = IA_ROOT . "/web/source/{$controller}/__init.php";
if (is_file($init)) {
	require $init;
}

$need_account_info = uni_need_account_info();
if (defined('FRAME') && $need_account_info) {
	if (!empty($_W['uniacid'])) {
		$_W['uniaccount'] = $_W['account'] = uni_fetch($_W['uniacid']);
		if (is_error($_W['account'])) {
			itoast('', url('account/display'));
		}
		$_W['acid'] = $_W['account']['acid'];
		$_W['weid'] = $_W['uniacid'];
	}
}

$actions = array();
$actions_path = file_tree(IA_ROOT . '/web/source/' . $controller);
foreach ($actions_path as $action_path) {
	$action_name = str_replace('.ctrl.php', '', basename($action_path));

	$section = basename(dirname($action_path));
	if ($section !== $controller) {
		$action_name = $section . '-' . $action_name;
	}
	$actions[] = $action_name;
}

if (empty($actions)) {
	header('location: ?refresh');
}

if (!in_array($action, $actions)) {
	$action = $action . '-' . $action;
}
if (!in_array($action, $actions)) {
	$action = $acl[$controller]['default'] ? $acl[$controller]['default'] : $actions[0];
}
$_W['iscontroller'] = current_operate_is_controller();
if (is_array($acl[$controller]['direct']) && in_array($action, $acl[$controller]['direct'])) {
		require _forward($controller, $action);
	exit();
}
if (!defined('FRAME')) {
	define('FRAME', '');
}
checklogin();
if (ACCOUNT_MANAGE_NAME_FOUNDER != $_W['role']) {
	if (ACCOUNT_MANAGE_NAME_UNBIND_USER == $_W['role']) {
		itoast('', url('user/third-bind'));
	}
	if (empty($_W['uniacid']) && in_array(FRAME, array('account', 'wxapp')) && 'store' != $_GPC['m']) {
		itoast('', url('account/display/platform'), 'info');
	}

	$acl = permission_build();
	if (in_array(FRAME, array('system', 'site', 'account_manage', 'platform', 'module', 'welcome', 'myself'))) {
		$checked_role = $_W['highest_role'];
	} else {
		$checked_role = $_W['role'];
	}
	if (empty($acl[$controller][$checked_role]) ||
		(!in_array($controller . '*', $acl[$controller][$checked_role]) && !in_array($action, $acl[$controller][$checked_role]))) {
		message('不能访问, 需要相应的权限才能访问！');
	}
	unset($checked_role);
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
		'runurl' => $_W['sitescheme'] . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
		'createtime' => TIMESTAMP,
	);
	pdo_insert('core_performance', $data);
}
function _forward($c, $a) {
	$file = IA_ROOT . '/web/source/' . $c . '/' . $a . '.ctrl.php';
	if (!file_exists($file)) {
		list($section, $a) = explode('-', $a);
		$file = IA_ROOT . '/web/source/' . $c . '/' . $section . '/' . $a . '.ctrl.php';
	}

	return $file;
}
function _calc_current_frames(&$frames) {
	global $_W, $controller, $action;
	$_W['page']['title'] = (isset($_W['page']['title']) && !empty($_W['page']['title'])) ? $_W['page']['title'] : ((2 == $frames['dimension'] && !('account' == $controller && 'welcome' == $action)) ? $frames['title'] : '');
	if (in_array(FRAME, array('account', 'wxapp'))) {
		$_W['breadcrumb'] = $_W['account']['name'];
	}
	if (in_array(FRAME, array('myself', 'message'))) {
		$_W['breadcrumb'] = $frames['title'];
	}
	if (defined('IN_MODULE')) {
		$_W['breadcrumb'] = ($_W['current_module']['name'] == 'store' ? '' : ('<a href="' . url('home/welcome', array('uniacid' => $_W['account']['uniacid'])) . '">' . $_W['account']['name'] . '</a> / ')) .  $_W['current_module']['title'];
	}
	if (empty($frames['section']) || !is_array($frames['section'])) {
		return true;
	}
	foreach ($frames['section'] as &$frame) {
		if (empty($frame['menu'])) {
			continue;
		}
		$finished = false;
		foreach ($frame['menu'] as $key => &$menu) {
			if (defined('IN_MODULE') && $menu['multilevel']) {
				foreach ($menu['childs'] as $module_child_key => $module_child_menu) {
					$query = parse_url($module_child_menu['url'], PHP_URL_QUERY);
					$server_query = parse_url($_W['siteurl'], PHP_URL_QUERY);
					if (0 === strpos($server_query, $query)) {
						$menu['childs'][$module_child_key]['active'] = 'active';
						break;
					}
				}
			} else {
				$query = parse_url($menu['url'], PHP_URL_QUERY);
				parse_str($query, $urls);
				if (empty($urls)) {
					continue;
				}
				if (defined('ACTIVE_FRAME_URL')) {
					$query = parse_url(ACTIVE_FRAME_URL, PHP_URL_QUERY);
					parse_str($query, $get);
				} else {
					$get = $_GET;
					$get['c'] = $controller;
					$get['a'] = $action;
				}
				if (!empty($do)) {
					$get['do'] = $do;
				}
				if (false !== strpos($get['do'], 'post') && !in_array($key, array('platform_menu', 'platform_masstask'))) {
					$_W['page']['title'] = '';
					continue;
				}
				$diff = array_diff_assoc($urls, $get);

				if (empty($diff) ||
					'platform_menu' == $key && 'menu' == $get['a'] && in_array($get['do'], array('display')) ||
					'platform_site' == $key && in_array($get['a'], array('style', 'article', 'category')) ||
					'mc_member' == $key && in_array($get['a'], array('editor', 'group', 'fields')) ||
					'profile_setting' == $key && in_array($get['a'], array('passport', 'tplnotice', 'notify', 'common')) ||
					'profile_payment' == $key && in_array($get['a'], array('refund')) ||
					'statistics_visit' == $key && in_array($get['a'], array('site', 'setting')) ||
					'platform_reply' == $key && in_array($get['a'], array('reply-setting')) ||
					'system_setting_thirdlogin' == $key && in_array($get['a'], array('thirdlogin')) ||
					'system_cloud_sms' == $key && in_array($get['a'], array('profile')) ||
					'wxapp_profile_payment' == $key && in_array($get['a'], array('refund'))) {
					$menu['active'] = ' active';
					$_W['page']['title'] = !empty($_W['page']['title']) ? $_W['page']['title'] : $menu['title'];
					$finished = true;
					break;
				}
			}
		}
		if ($finished) {
			break;
		}
	}
	return true;
}