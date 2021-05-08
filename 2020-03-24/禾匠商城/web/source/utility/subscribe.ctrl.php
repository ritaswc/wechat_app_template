<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$dos = array('receive', '');
$do = in_array($do, $dos) ? $do : '';

$_W['uniacid'] = intval($_GPC['i']);

if (empty($_W['uniacid'])) {
	iajax(1, '请先指定公众号');
}
$_W['account'] = uni_fetch($_W['uniacid']);

if ('receive' == $do) {
	ignore_user_abort(true);
	set_time_limit(30);

	$modulename = $_GPC['modulename'];
	$request = json_decode(html_entity_decode($_GPC['request']), true);
	$response = json_decode(html_entity_decode($_GPC['response']), true);
	$message = json_decode(html_entity_decode($_GPC['message']), true);

	$module = module_fetch($modulename);
	if (!empty($module)) {
		$module_receiver = WeUtility::createModuleReceiver($modulename);
		$module_receiver->message = $message;
		$module_receiver->params = $request;
		$module_receiver->response = $response;
		$module_receiver->keyword = $request['keyword'];
		$module_receiver->module = $module;
		$module_receiver->uniacid = $_W['uniacid'];
		if (method_exists($module_receiver, 'receive')) {
			@$module_receiver->receive();
		}
	}
}