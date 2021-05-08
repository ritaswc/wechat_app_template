<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('login', 'binding');
$do = in_array($do, $dos) ? $do : 'login';

$settings = $_W['setting']['copyright'];
if(empty($settings) || !is_array($settings)) {
	$settings = array();
} else {
	$settings['slides'] = iunserializer($settings['slides']);
}

if ($do == 'login') {
	$_W['page']['title'] = '用户登录/注册设置 - 登录设置';
	if (checksubmit('submit')) {
		$settings['verifycode'] = intval($_GPC['verifycode']);
		$settings['welcome_link'] = intval($_GPC['welcome_link']);

		setting_save($settings, 'copyright');
		itoast('更新设置成功！', '', 'success');
	}
}

if ($do == 'binding') {
	$_W['page']['title'] = '用户登录/注册设置 - 绑定设置';
	if (checksubmit('submit')) {
		$settings['bind'] = safe_gpc_string($_GPC['bind']);
		$settings['oauth_bind'] = intval($_GPC['oauth_bind']);

		setting_save($settings, 'copyright');
		itoast('更新设置成功！', '', 'success');
	}

}

template('system/user-setting');

