<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('account');
load()->func('communication');
load()->library('qrcode');

$dos = array('display', 'change', 'qr', 'chat', 'down_qr');
$do = !empty($_GPC['do']) && in_array($do, $dos) ? $do : 'display';
permission_check_account_user('platform_qr_qr');

if ('display' == $do) {
	template('platform/url2qr');
}

if ('change' == $do) {
	if ($_W['ispost'] && $_W['isajax']) {
		$longurl = trim($_GPC['longurl']);
		$token = $_W['account']->getAccessToken();

		$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$token}";
		$send = array();
		$send['action'] = 'long2short';
		$send['long_url'] = $longurl;
		$response = ihttp_request($url, json_encode($send));
		if (is_error($response)) {
			$result = error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			$result = error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			$result = error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}");
		}
		if (is_error($result)) {
			iajax(-1, $result['message'], '');
		}
		iajax(0, $result, '');
	} else {
		iajax(1, 'error', '');
	}
}

if ('qr' == $do) {
	$url = $_GPC['url'];
	$errorCorrectionLevel = 'L';
	$matrixPointSize = '5';
	QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
	exit();
}

if ('down_qr' == $do) {
	$qrlink = $_GPC['qrlink'];
	$errorCorrectionLevel = 'L';
	$matrixPointSize = '5';
	$qr_pic = QRcode::png($qrlink, false, $errorCorrectionLevel, $matrixPointSize);
	$name = random(8);
	header('cache-control:private');
	header('content-type:image/jpeg');
	header('content-disposition: attachment;filename="' . $name . '.jpg"');
	readfile($qr_pic);
	exit;
}