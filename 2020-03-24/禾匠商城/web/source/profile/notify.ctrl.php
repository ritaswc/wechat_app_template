<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('communication');

$dos = array('mail');
$do = in_array($do, $dos) ? $do : 'mail';
permission_check_account_user('profile_setting');

if ('mail' == $do) {
	$notify = uni_setting_load('notify');
	$notify = $notify['notify'];
	if (!is_array($notify)) {
		$notify = array();
	}
	if (checksubmit('submit')) {
		$notify['mail'] = array(
			'username' => $_GPC['username'],
			'password' => $_GPC['password'],
			'smtp' => $_GPC['smtp'],
			'sender' => $_GPC['sender'],
			'signature' => $_GPC['signature'],
		);
		if (1 == $_GPC['status']) {
			$notify['mail']['smtp']['type'] = safe_gpc_string($_GPC['type']);
		} else {
			$notify['mail']['smtp']['type'] = '';
		}
		uni_setting_save('notify', $notify);
		$result = ihttp_email($notify['mail']['username'], $_W['account']['name'] . '验证邮件' . date('Y-m-d H:i:s'), '如果您收到这封邮件则表示您系统的发送邮件配置成功！');
		if (is_error($result)) {
			itoast('配置失败，请检查配置信息', '', 'error');
		} else {
			itoast('配置成功！', url('profile/notify/mail'), 'success');
		}
	}
}
template('profile/notify');