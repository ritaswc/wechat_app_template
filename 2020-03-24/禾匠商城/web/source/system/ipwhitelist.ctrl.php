<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('site');
load()->model('setting');

$dos = array('display', 'change_status', 'add', 'delete');
$do = in_array($_GPC['do'], $dos) ? $do : 'display';

$ip_lists = setting_load('ip_white_list');
$ip_lists = $ip_lists['ip_white_list'];
if ('display' == $do) {
	$keyword = trim($_GPC['keyword']);
	$lists = $ip_lists;
	if (!empty($keyword)) {
		$lists = array();
		foreach ($ip_lists as $ip => $ip_info) {
			if (strexists($ip, $keyword)) {
				$lists[$ip] = $ip_info;
			}
		}
	}
}

if ('change_status' == $do) {
	$ip = trim($_GPC['ip']);
	$status = $ip_lists[$ip]['status'];
	$status = empty($status) ? 1 : 0;
	$ip_lists[$ip]['status'] = $status;
	$update = setting_save($ip_lists, 'ip_white_list');
	if ($update) {
		iajax(0, '');
	}
	iajax(-1, '更新失败', url('system/ipwhitelist'));
}

if ('add' == $do) {
	$ips = $_GPC['ips'];
	$ip_data = site_ip_add($ips);
	if (is_error($ip_data)) {
		iajax(-1, $ip_data['message']);
	}
	iajax(0, '添加成功', url('system/ipwhitelist'));
}

if ('delete' == $do) {
	$ip = trim($_GPC['ip']);
	if (empty($ip)) {
		itoast('参数错误');
	}
	unset($ip_lists[$ip]);
	$update = setting_save($ip_lists, 'ip_white_list');
	itoast('删除成功', url('system/ipwhitelist'));
}
template('system/ip-list');