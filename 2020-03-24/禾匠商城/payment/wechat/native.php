<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';

load()->classs('pay/pay');
$pay = Pay::create();
$input= file_get_contents('php://input');
$input = $pay->parseResult($input);
if(is_error($input)) {
	$pay->replyErrorNotify($input['message']);
	exit();
}
$result = $pay->buildNativePrepayid($input['product_id']);
if(is_error($result)) {
	$pay->replyErrorNotify($result['message']);
	exit();
}
echo array2xml($result);

echo 90;
$log = table('core_paylog')->where(array('plid' => intval($input['product_id'])))->get();
$site = WeUtility::createModuleSite($log['module']);
if(!is_error($site)) {
	$method = 'scanResult';
	if (method_exists($site, $method)) {
		$ret = array();
		$ret['weid'] = $log['weid'];
		$ret['uniacid'] = $log['uniacid'];
		$ret['acid'] = $log['acid'];
		$ret['result'] = 'success';
		$ret['type'] = $log['type'];
		$ret['from'] = 'notify';
		$ret['tid'] = $log['tid'];
		$ret['uniontid'] = $log['uniontid'];
		$ret['openid'] = empty($input['openid']) ? $log['openid'] : $input['openid'];
		$ret['is_follow'] = $input['is_subscribe'] == 'Y' ? 1 : 0;
		$ret['fee'] = $log['fee'];
		$ret['tag'] = $log['tag'];
		$ret['is_usecard'] = $log['is_usecard'];
		$ret['card_type'] = $log['card_type'];
		$ret['card_fee'] = $log['card_fee'];
		$ret['card_id'] = $log['card_id'];
		$site->$method($ret);
	}
}

