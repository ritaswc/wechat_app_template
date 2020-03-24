<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('cloud');

$dos = array('smsSign', 'display');
$do = in_array($do, $dos) ? $do : 'display';

if ('smsSign' == $do) {
	$data = cloud_sms_sign(intval($_GPC['parames']['page']));
	if (isset($data['data'][0]['createtime']) && is_numeric($data['data'][0]['createtime'])) {
		foreach ($data['data'] as &$item) {
			$item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
		}
	}
	iajax(0, $data['data']);
}
template('cloud/sms-sign');
