<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('payment');
load()->classs('uploadedfile');

$dos = array('save_setting', 'display');
$do = in_array($do, $dos) ? $do : 'display';
permission_check_account_user('profile_payment_refund', true, 'wxapp');

if ('display' == $do) {
	$setting = uni_setting_load('payment', $_W['uniacid']);
	$setting = $setting['payment'];
	if (empty($setting['wechat_refund'])) {
		$setting['wechat_refund'] = array('switch' => 0, 'key' => '', 'cert' => '');
	}
	$has_cert = !empty($setting['wechat_refund']['cert']); 	$has_key = !empty($setting['wechat_refund']['key']); 	$open_or_close = !empty($setting['wechat_refund']['switch']); }

if ('save_setting' == $do) {
	$type = $_GPC['type'];
	$is_open = '1' == $_GPC['switch'] ? 1 : 0;
	$setting = uni_setting_load('payment', $_W['uniacid']);
	$pay_setting = $setting['payment'];

	$files = UploadedFile::createFromGlobal();
	$cert = isset($files['cert']) ? $files['cert'] : null; 	$private_key = isset($files['key']) ? $files['key'] : null; 	$cert_content = $pay_setting['wechat_refund']['cert']; 	$private_key_content = $pay_setting['wechat_refund']['key']; 	$open_or_close = !empty($pay_setting['wechat_refund']['switch']); 

	
	if ($cert && $cert->isOk()) { 		$cert_content = $cert->getContent();
				$cert_content = authcode($cert_content, 'ENCODE');
	}
	
	if ($private_key && $private_key->isOk()) { 		$key_content = $private_key->getContent();
		$private_key_content = authcode($key_content, 'ENCODE');
	}
	if ($is_open) {
		if (!$cert_content) {
			itoast('请上传apiclient_cert.pem证书', '', 'info');
		}

		if (!$private_key_content) {
			itoast('请上传apiclient_key.pem证书', '', 'info');
		}
	}

	$pay_setting['wechat_refund'] = array('cert' => $cert_content,
		'key' => $private_key_content, 'switch' => $is_open, 'version' => 1, ); 
	uni_setting_save('payment', $pay_setting);
	itoast('设置成功', '', 'success');
}

template('wxapp/refund');