<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('get_setting', 'display', 'save_setting');
$do = in_array($do, $dos) ? $do : 'display';
permission_check_account_user('wxapp_payment_pay');

$setting = uni_setting_load('payment', $_W['uniacid']);
$pay_setting = $setting['payment'];
$wxapp_info = miniapp_fetch($_W['uniacid']);

if ('get_setting' == $do) {
	iajax(0, $pay_setting, '');
}

if ('display' == $do) {
	if (empty($pay_setting) || empty($pay_setting['wechat'])) {
		$pay_setting = array(
			'wechat' => array('mchid' => '', 'signkey' => ''),
		);
	}
}

if ('save_setting' == $do) {
	if (!$_W['isajax'] || !$_W['ispost']) {
		iajax(-1, '非法访问');
	}
	$type = $_GPC['type'];
	if ('wechat' != $type) {
		iajax(-1, '参数错误');
	}
	$param = $_GPC['param'];
	$param['account'] = $_W['acid'];
	$pay_setting[$type] = $param;
	$payment = iserializer($pay_setting);
	uni_setting_save('payment', $payment);
	iajax(0, '设置成功', url('account/display', array('do' => 'switch', 'uniacid' => $_W['uniacid'])));
}
template('wxapp/payment');