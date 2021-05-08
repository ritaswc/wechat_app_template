<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');

$dos = array('display');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';

$module_name = trim($_GPC['m']);
$uniacid = intval($_GPC['uniacid']);
$modulelist = uni_modules();
$module = $_W['current_module'] = $modulelist[$module_name];

if (empty($module)) {
	itoast('抱歉，你操作的模块不能被访问！');
}

if ('display' == $do) {
	$account_info = uni_fetch($uniacid);

	$uni_account_module = pdo_get('uni_account_modules', array('uniacid' => $uniacid, 'module' => $module_name));
	$settings = iunserializer($uni_account_module['settings']);

	$passive_link_accounts = array();
	if (!empty($settings) && !empty($settings['passive_link_uniacid'])) {
		foreach ($settings['passive_link_uniacid'] as $passive_lin_uniacid) {
			$passive_link_accounts[] = uni_fetch($passive_lin_uniacid);
		}
	}

	template('module/link-account');
}
