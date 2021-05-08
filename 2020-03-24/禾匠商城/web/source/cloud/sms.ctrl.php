<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('cloud');
load()->func('communication');

$dos = array('sms', 'smsLog', 'smsSign', 'smsTrade', 'settingSign', 'change_setting');
$do = in_array($do, $dos) ? $do : 'sms';

$user_expire = setting_load('user_expire');
$user_expire = !empty($user_expire['user_expire']) ? $user_expire['user_expire'] : array();

$account_sms_expire = setting_load('account_sms_expire');
$account_sms_expire = !empty($account_sms_expire['account_sms_expire']) ? $account_sms_expire['account_sms_expire'] : array();

$api_sms_expire = setting_load('api_sms_expire');
$api_sms_expire = !empty($api_sms_expire['api_sms_expire']) ? $api_sms_expire['api_sms_expire'] : array();

if ('sms' == $do) {
	$sms_info = cloud_sms_info();
	if (!empty($sms_info['sms_sign'])) {
		foreach ($sms_info['sms_sign'] as $item) {
			$cloud_sms_signs[$item] = $item;
		}
	}
	$setting_sms_sign = setting_load('site_sms_sign');
	$setting_sms_sign = !empty($setting_sms_sign['site_sms_sign']) ? $setting_sms_sign['site_sms_sign'] : array();
	$setting_sms_sign['register'] = !empty($setting_sms_sign['register']) ? $setting_sms_sign['register'] : '';
	$setting_sms_sign['find_password'] = !empty($setting_sms_sign['find_password']) ? $setting_sms_sign['find_password'] : '';
	$setting_sms_sign['user_expire'] = !empty($setting_sms_sign['user_expire']) ? $setting_sms_sign['user_expire'] : '';
	$setting_sms_sign['account_expire'] = !empty($setting_sms_sign['account_expire']) ? $setting_sms_sign['account_expire'] : '';
	$setting_sms_sign['api_expire'] = !empty($setting_sms_sign['api_expire']) ? $setting_sms_sign['api_expire'] : '';

	$user_expire['day'] = !empty($user_expire['day']) ? $user_expire['day'] : 1;
	$user_expire['status'] = !empty($user_expire['status']) ? $user_expire['status'] : 0;

	$account_sms_expire['day'] = !empty($account_sms_expire['day']) ? $account_sms_expire['day'] : 1;
	$account_sms_expire['status'] = !empty($account_sms_expire['status']) ? $account_sms_expire['status'] : 0;

	$api_sms_expire['num'] = !empty($api_sms_expire['num']) ? $api_sms_expire['num'] : 3000;
	$api_sms_expire['status'] = !empty($api_sms_expire['status']) ? $api_sms_expire['status'] : 0;

	template('cloud/sms');
}

if ('smsSign' == $do) {
	$data = cloud_sms_sign(intval($_GPC['parames']['page']));
	if (isset($data['data'][0]['createtime']) && is_numeric($data['data'][0]['createtime'])) {
		foreach ($data['data'] as &$item) {
			$item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
		}
	}
	iajax(0, $data['data']);
}

if ('smsTrade' == $do) {
	$params = safe_gpc_array($_GPC['params']);
	$params['page'] = empty($params['page']) ? 1 : intval($params['page']);
	if (!empty($params['time'][1])) {
		$params['time'][1] += 86400;
	} else {
		$params['time'] = array();
	}
	$data = cloud_sms_trade($params['page'], $params['time']);

	if (isset($data['data'][0]['createtime']) && is_numeric($data['data'][0]['createtime'])) {
		foreach ($data['data'] as &$item) {
			$item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
		}
	}
	iajax(0, $data['data']);
}

if ('smsLog' == $do) {
	$params = safe_gpc_array($_GPC['params']);
	$params['page'] = empty($params['page']) ? 1 : intval($params['page']);
	$params['page_size'] = empty($params['page_size']) ? 10 : intval($params['page_size']);
	$params['mobile'] = !is_numeric($params['mobile']) || empty($params['mobile']) ? 0 : $params['mobile'];
	if (!empty($params['time'][1])) {
		$params['time'][1] += 86400;
	} else {
		$params['time'] = array();
	}

	$data = cloud_sms_log($params['mobile'], $params['time'], $params['page'], $params['page_size']);

	if (is_error($data)) {
		iajax(-1, $data['message']);
	}
	if (isset($data['data'][0]['createtime']) && is_numeric($data['data'][0]['createtime'])) {
		foreach ($data['data'] as &$item) {
			$item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
		}
	}
	iajax(0, $data['data']);
}

if ('settingSign' == $do) {
	$setting_sms_sign = setting_load('site_sms_sign');
	$setting_sms_sign = !empty($setting_sms_sign['site_sms_sign']) ? $setting_sms_sign['site_sms_sign'] : array();

	if (isset($_GPC['register'])) {
		$setting_sms_sign['register'] = safe_gpc_string($_GPC['register']);
	}
	if (isset($_GPC['find_password'])) {
		$setting_sms_sign['find_password'] = safe_gpc_string($_GPC['find_password']);
	}
	if (isset($_GPC['user_expire'])) {
		$setting_sms_sign['user_expire'] = safe_gpc_string($_GPC['user_expire']);
	}
	if (isset($_GPC['account_sms_expire'])) {
		$setting_sms_sign['account_sms_expire'] = safe_gpc_string($_GPC['account_sms_expire']);
	}
	if (isset($_GPC['api_sms_expire'])) {
		$setting_sms_sign['api_sms_expire'] = safe_gpc_string($_GPC['api_sms_expire']);
	}
	$result = setting_save($setting_sms_sign, 'site_sms_sign');
	if (is_error($result)) {
		iajax(-1, '设置失败', url('cloud/sms'));
	}
	iajax(0, '设置成功', url('cloud/sms'));
}

if ('change_setting' == $do) {
	$setting_name = safe_gpc_string($_GPC['setting_name']);
	$type = safe_gpc_string($_GPC['type']);
	if (!in_array($setting_name, array('user_expire', 'account_sms_expire', 'api_sms_expire')) || !in_array($type, array('status', 'day', 'num', 'status_store_redirect'))) {
		iajax(-1, '参数错误');
	}
	$setting = setting_load($setting_name);
	$setting = !empty($setting[$setting_name]) ? $setting[$setting_name] : array();
	$setting[$type] = safe_gpc_int($_GPC[$type]);
	$result = setting_save($setting, $setting_name);
	if (is_error($result)) {
		iajax(-1, '设置失败', referer());
	}
	iajax(0, '设置成功', referer());
}