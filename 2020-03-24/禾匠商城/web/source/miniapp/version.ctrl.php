<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('phoneapp');
load()->model('welcome');

$dos = array('home', 'display');
$do = in_array($do, $dos) ? $do : 'home';

if ('display' == $do) {
	if ($_W['account']['type_sign'] == 'phoneapp') {
		$version_list = phoneapp_version_all($_W['uniacid']);
	} else {
		$version_list = miniapp_version_all($_W['uniacid']);
	}
	if (!empty($version_list)) {
		foreach ($version_list as &$version) {
			$version['module'] = is_array($version['modules']) ? current($version['modules']) : array();
		}
	}
	$_W['breadcrumb'] = $_W['account']['name'];
	template('miniapp/version-display');
}

if ('home' == $do) {
	$version_id = intval($_GPC['version_id']);
	$wxapp_info = miniapp_fetch($_W['uniacid']);
	if ($_GPC['miniapp_version_referer']) {
		itoast('', url('miniapp/version/display'));
	}
	if (!empty($version_id)) {
		$version_info = miniapp_version($version_id);
	}
	$notices = welcome_notices_get();
	template('miniapp/version-home');
}