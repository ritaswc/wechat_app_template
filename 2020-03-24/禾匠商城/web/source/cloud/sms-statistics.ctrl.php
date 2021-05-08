<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('cloud');
load()->func('communication');

$dos = array('smsLog', 'display');
$do = in_array($do, $dos) ? $do : 'display';

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

	if (empty($params['status'])) {
		$params['status'] = -1;
	} else {
		$params['status'] = $params['status'] == 1 ? 0 : 1;
	}
	$data = cloud_sms_log($params['mobile'], $params['time'], $params['page'], $params['page_size'], $params['status']);

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
template('cloud/sms-statistics');
