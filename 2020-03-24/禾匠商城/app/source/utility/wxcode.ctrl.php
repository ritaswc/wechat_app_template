<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->library('qrcode');
load()->func('communication');

$dos = array('verifycode', 'image', 'qrcode');
$do = in_array($do, $dos) ? $do : 'verifycode';

$_W['uniacid'] = intval($_GPC['uniacid']);
if($do == 'verifycode') {
		$username = trim($_GPC['username']);
	$response = ihttp_get("https://mp.weixin.qq.com/cgi-bin/verifycode?username={$username}&r=" . TIMESTAMP);
	if(!is_error($response)) {
		isetcookie('code_cookie', $response['headers']['Set-Cookie']);
		header('Content-type: image/jpg');
		echo $response['content'];
		exit();
	}
} elseif($do == 'image') {
		$image = trim($_GPC['attach']);
	if(empty($image)) exit();
		if (!starts_with($image, array('http://mmbiz.qpic.cn/mmbiz_', 'https://mmbiz.qpic.cn/mmbiz_'))) {
		exit();
	}
	$content = ihttp_request($image, '', array('CURLOPT_REFERER' => 'http://www.qq.com'));
	header('Content-Type:image/jpg');
	echo $content['content'];
	exit();
} elseif ($do == 'qrcode') {
	$errorCorrectionLevel = "L";
	$matrixPointSize = "8";
	$text = trim($_GPC['text']);
	QRcode::png($text, false, $errorCorrectionLevel, $matrixPointSize);
	exit();
}
