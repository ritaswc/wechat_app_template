<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('site');
$dos = array('display', 'login_out');
$do = in_array($do, $dos) ? $do : 'display';
load()->func('tpl');
$card_setting = table('mc_card')->where(array('uniacid' => $_W['uniacid']))->get();
$uni_setting = table('uni_settings')
	->where(array('uniacid' => $_W['uniacid']))
	->select('exchange_enable')
	->get();
$setting = uni_setting_load(array('passport'), $_W['uniacid']);
if($do == 'login_out') {
	unset($_SESSION);
	session_destroy();
	isetcookie('logout', 1, 60);
	$logoutjs = "<script language=\"javascript\" type=\"text/javascript\">window.location.href=\"" . url('auth/login/') . "\";</script>";
	exit($logoutjs);
}
if ($do == 'display') {
	$navs = site_app_navs('profile');
	$modules = uni_modules();
	$groups = $others = array();
	$reg = '/^tel:(\d+)$/';
	if(!empty($navs)) {
		foreach($navs as $row) {
			$row['url'] = tourl($row['url']);
			if(!empty($row['module'])) {
				$groups[$row['module']][] = $row;
			} else {
				$others[] = $row;
			}
		}
	}
	$profile = mc_fetch($_W['member']['uid'], array('nickname', 'avatar', 'mobile', 'groupid'));
	$mcgroups = mc_groups();
	$profile['group'] = $mcgroups[$profile['groupid']];
	if (empty($setting['passport']['focusreg'])) {
		$reregister = false;
		if ($_W['member']['email'] == md5($_W['openid']).'@we7.cc') {
			$reregister = true;
		}
	}
}

template('mc/home');