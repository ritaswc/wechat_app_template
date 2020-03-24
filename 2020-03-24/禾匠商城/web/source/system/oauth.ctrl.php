<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('setting');
load()->model('user');

$dos = array('display', 'save_oauth');
$do = in_array($_GPC['do'], $dos)? $do : 'display';

$oauth = setting_load('global_oauth');
$oauth = !empty($oauth['global_oauth']) ? $oauth['global_oauth'] : array();

if ($do == 'display') {
	$user_have_accounts = user_borrow_oauth_account_list();
	$oauth_accounts = $user_have_accounts['oauth_accounts'];
}

if ($do == 'save_oauth') {
	if (!$_W['isajax'] || !$_W['ispost']) {
		iajax(-1, '添加失败');
	}

	$oauth['oauth']['account'] = intval($_GPC['account']);

	$oauth['oauth']['host'] = safe_gpc_url(rtrim($_GPC['host'],'/'), false);
	if (!empty($_GPC['host']) && empty($oauth['oauth']['host'])) {
		iajax(-1, '域名不合法');
	}

	$result = setting_save($oauth, 'global_oauth');
	if (is_error($result)) {
		iajax(-1, '添加失败');
	}
	iajax(0, '添加成功', url('system/oauth'));

}
template('system/oauth');