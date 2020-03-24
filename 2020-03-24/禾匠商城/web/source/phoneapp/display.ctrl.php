<?php

defined('IN_IA') or exit('Access Denied');
load()->model('phoneapp');
load()->model('account');

if ($do == 'home') {
	$last_uniacid = uni_account_last_switch();
	$url = url('account/display', array('type' => PHONEAPP_TYPE_SIGN));
	if (empty($last_uniacid)) {
		itoast('', $url, 'info');
	}
	if (!empty($last_uniacid) && $last_uniacid != $_W['uniacid']) {
		uni_account_switch($last_uniacid, '', PHONEAPP_TYPE_SIGN);
	}
	$permission = permission_account_user_role($_W['uid'], $last_uniacid);
	if (empty($permission)) {
		itoast('', $url, 'info');
	}
	$last_version = phoneapp_fetch($last_uniacid);
	if (!empty($last_version)) {
		$url = url('phoneapp/version/home', array('version_id' => $last_version['version']['id']));
	}
	itoast('', $url, 'info');
}
