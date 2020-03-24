<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');
load()->model('reply');
load()->model('miniapp');
load()->model('welcome');
load()->model('cache');

$dos = array('display', 'welcome_display', 'get_module_info', 'get_module_replies', 'get_module_accounts', 'get_module_covers', 'change_enter_status');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';

$module_name = trim($_GPC['m']);
$uniacid = intval($_GPC['uniacid']);
$module = $_W['current_module'] = module_fetch($module_name, false);
$type = $_W['account']->typeSign;
if (!empty($module) && empty($module['is_delete']) && !empty($module['recycle_info'])) {
	foreach ($module['recycle_info'] as $key => $value)
	{
		if ($type.'_support' == $key && $value == MODULE_RECYCLE_UNINSTALL_IGNORE) {
			$module = array();
			break;
		}
		if ( $type.'_support' == $key && $value == MODULE_RECYCLE_INSTALL_DISABLED ){
			$expire_notice = module_expire_notice();
			itoast($expire_notice, url('home/welcome'), 'info');
		}
	}
}

if (empty($module) || !empty($module['is_delete'])) {
	$_W['current_module'] = array();
	cache_build_account_modules($uniacid);
	itoast('抱歉，你操作的模块不能被访问！', url('home/welcome'), 'info');
}

if ('display' == $do) {
	user_save_operate_history(USERS_OPERATE_TYPE_MODULE, $module_name);
	$notices = welcome_notices_get();
	template('module/welcome');
}

if ('welcome_display' == $do) {
	$site = WeUtility::createModule($module_name);
	if (!is_error($site)) {
		$method = 'welcomeDisplay';
		if (method_exists($site, $method)) {
			define('FRAME', 'module_welcome');
			$entries = module_entries($module_name, array('menu', 'home', 'profile', 'shortcut', 'cover', 'mine'));
			$site->$method($entries);
			exit;
		}
	}
}

if ('get_module_info' == $do) {
	$uni_modules_talbe = table('uni_modules');
	$uni_modules_talbe->searchWithModuleName($module_name);
	$module_info = $uni_modules_talbe->getModulesByUid($_W['uid'], $uniacid);
	$module_info = current($module_info['modules']);
	$module_info['welcome_display'] = false;

		$site = WeUtility::createModule($module_name);
	if (!is_error($site) && method_exists($site, 'welcomeDisplay')) {
		$module_info['welcome_display'] = true;
		$module_info['direct_enter_status'] = module_get_direct_enter_status($module_name);
	}

	$data = array(
		'module_info' => $module_info,
	);
	iajax(0, $data);
}

if ('get_module_replies' == $do) {
		$condition = "uniacid = :uniacid AND module != 'cover' AND module != 'userapi'";
	$condition .= ' AND `module` = :type';
	$params[':type'] = $module_name;
	$params[':uniacid'] = $uniacid;
	$replies = reply_search($condition, $params);

	if (!empty($replies)) {
		foreach ($replies as &$item) {
			$condition = '`rid`=:rid';
			$params = array();
			$params[':rid'] = $item['id'];
			$item['keywords'] = reply_keywords_search($condition, $params);
			$item['allreply'] = reply_contnet_search($item['id']);
			$entries = module_entries($item['module'], array('rule'), $item['id']);

			if (!empty($entries)) {
				$item['options'] = $entries['rule'];
			}
						if (!in_array($item['module'], array('basic', 'news', 'images', 'voice', 'video', 'music', 'wxcard', 'reply'))) {
				$item['module_info'] = module_fetch($item['module']);
			}
		}
		unset($item);
	}
	iajax(0, $replies);
}

if ('get_module_accounts' == $do) {
		$account_info = uni_fetch($uniacid);
	if (ACCOUNT_MANAGE_NAME_CLERK == $account_info['current_user_role']) {
		unset($account_info['switchurl']);
	}
		$sub_account_uniacids = table('uni_link_uniacid')->getSubUniacids($uniacid, $module_name);
	$link_accounts = array();
	if (!empty($sub_account_uniacids)) {
		foreach ($sub_account_uniacids as $sub_uniacid) {
			$sub_account_info = uni_fetch($sub_uniacid);
			if (ACCOUNT_MANAGE_NAME_CLERK == $sub_account_info['current_user_role']) {
				unset($sub_account_info['switchurl']);
			}
			$link_accounts[] = $sub_account_info;
		}
	}

	$data = array('account_info' => $account_info, 'link_accounts' => $link_accounts);
	iajax(0, $data);
}

if ('get_module_covers' == $do) {
		$entries = module_entries($module_name);
	if (!empty($entries['cover'])) {
		$covers = $entries['cover'];
		$cover_eid = current($covers);
		$cover_eid = $cover_eid['eid'];
	}
	iajax(0, array('covers' => $covers, 'cover_eid' => $cover_eid));
}

if ('change_enter_status' == $do) {
	$result = module_change_direct_enter_status($module_name);
	if ($result) {
		iajax(0, true);
	} else {
		iajax(-1, '修改失败！');
	}
}