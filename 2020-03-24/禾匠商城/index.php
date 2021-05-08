<?php
/**
 * [WeEngine System] Copyright (c) 20191214152815 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

require './framework/bootstrap.inc.php';

$host = $_SERVER['HTTP_HOST'];
if (!empty($host)) {
	$bindhost = pdo_fetch("SELECT * FROM ".tablename('site_multi')." WHERE bindhost = :bindhost", array(':bindhost' => $host));
	if (!empty($bindhost)) {
		header("Location: ". $_W['siteroot'] . 'app/index.php?i='.$bindhost['uniacid'].'&t='.$bindhost['id']);
		exit;
	}
	
		$pc_bind = pdo_get('uni_settings', array('bind_domain IN ' => array('http://' . $host, 'https://' . $host), 'default_module <>' => ''), array('uniacid', 'default_module', 'bind_domain'));
		if (!empty($pc_bind)) {
			$account_type = pdo_getcolumn('account', array('uniacid' => $pc_bind['uniacid']), 'type');
			if ($account_type == ACCOUNT_TYPE_WEBAPP_NORMAL) {
				$_W['uniacid'] = $pc_bind['uniacid'];
				$_W['account'] = array('type' => $account_type);
				$url = module_app_entries($pc_bind['default_module'], array('cover'));
				header('Location: ' . $pc_bind['bind_domain'] . '/app/' . $url['cover'][0]['url']);
				exit;
			}
		}
	
}
if($_W['os'] == 'mobile' && (!empty($_GPC['i']) || !empty($_SERVER['QUERY_STRING']))) {
	header('Location: ' . $_W['siteroot'] . 'app/index.php?' . $_SERVER['QUERY_STRING']);
} else {
	header('Location: ' . $_W['siteroot'] . 'web/index.php?' . (!empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : 'c=account&a=display'));
}