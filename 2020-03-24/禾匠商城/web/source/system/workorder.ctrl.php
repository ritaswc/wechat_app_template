<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->classs('cloudapi');
load()->model('message');

if ('display' == $do) { 	$message_id = intval($_GPC['message_id']);
	message_notice_read($message_id);
	$siteurl = $_W['siteroot'];
	$cloud = new CloudApi();
	$uuid = $_GPC['uuid'];
	$data = $cloud->get('system', 'workorder', array('do' => 'siteworkorder'), 'json');
	if (is_error($data)) {
		$extend_buttons = array(
			'confirm' => array(
				'url' => referer(),
				'class' => 'btn btn-primary',
				'title' => '确认',
			),
			'goto' => array(
				'url' => 'https://wo.w7.cc',
				'class' => 'btn btn-default',
				'target' => '_blank',
				'title' => '跳转云工单',
			),
		);
		message('微擎云服务链接中断。请恢复后再进行尝试。或者直接登录微擎应用商城进行工单发布。', referer(), 'expired', '', $extend_buttons);
	}
	$iframe_url = $data['data']['url'] . '&from=' . urlencode($siteurl) . '&uuid=' . $uuid;
	template('system/workorder');
}

if ('module' == $do) { 	$name = trim($_GPC['name']);
	if (empty($name)) {
		itoast('模块参数错误');
	}
	$module = module_fetch($name);
	if (!$module) {
		itoast('未找到模块');
	}
	$module_name = $name;
	$module_version = $module['version'];
	$issystem = $module['issystem'];
	$module_type = 'module';
	$from = urlencode($_W['siteroot']);
	$param = http_build_query(compact('module_name', 'module_version', 'module_type', 'from'));
	$cloud = new CloudApi();
	$data = $cloud->get('system', 'workorder', array('do' => 'siteworkorder'), 'json');
	if (is_error($data)) {
		echo json_encode(array('errno' => 0, 'message' => '无权限进入工单系统'));
		exit;
	}
	$iframe_url = $data['data']['url'] . '&' . $param;
	echo json_encode(array('errno' => 0, 'message' => '', 'data' => array('url' => $iframe_url)));
	exit;
}
