<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('welcome');
load()->model('miniapp');

$dos = array('home', 'get_daily_visittrend', 'display');
$do = in_array($do, $dos) ? $do : 'home';
$_W['page']['title'] = '小程序 - 管理';

$wxapp_info = miniapp_fetch($_W['uniacid']);

if ($do == 'display') {
	$wxapp_version_list = miniapp_version_all($_W['uniacid']);
	template('wxapp/version-display');
}
