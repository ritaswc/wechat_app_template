<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

include_once IA_ROOT . '/payment/unionpay/upacp_sdk_php/utf8/func/log.class.php';
define('SDK_CVN2_ENC', 0);
define('SDK_DATE_ENC', 0);
define('SDK_PAN_ENC', 0);

define('SDK_FRONT_TRANS_URL', 'https://gateway.95516.com/gateway/api/frontTransReq.do');
define('SDK_BACK_TRANS_URL', 'https://gateway.95516.com/gateway/api/backTransReq.do');
define('SDK_BATCH_TRANS_URL', 'https://gateway.95516.com/gateway/api/batchTrans.do');
define('SDK_SINGLE_QUERY_URL', 'https://gateway.95516.com/gateway/api/batchTrans.do');
define('SDK_FILE_QUERY_URL', 'https://filedownload.95516.com/');
define('SDK_Card_Request_Url', 'https://gateway.95516.com/gateway/api/cardTransReq.do');
define('SDK_App_Request_Url', 'https://gateway.95516.com/gateway/api/appTransReq.do');

define('SDK_FRONT_NOTIFY_URL', $_W['siteroot'] . 'pay.php');
define('SDK_BACK_NOTIFY_URL', $_W['siteroot'] . 'notify.php');
define('SDK_FILE_DOWN_PATH', 0);
define('SDK_LOG_FILE_PATH', IA_ROOT . '/data/logs/');
if (!empty($_W['config']['setting']['development'])) {
	define('SDK_LOG_LEVEL', PhpLog::INFO);
} else {
	define('SDK_LOG_LEVEL', PhpLog::OFF);
}
if (!file_exists(IA_ROOT . '/attachment/unionpay/PM_'.$_W['uniacid'].'_acp.pfx')) {
	message('缺少支付证书，请联系管理员！');
}
define('SDK_SIGN_CERT_PATH', IA_ROOT . '/attachment/unionpay/PM_'.$_W['uniacid'].'_acp.pfx');
define('SDK_SIGN_CERT_PWD', $payment['signcertpwd']);
define('SDK_ENCRYPT_CERT_PATH', IA_ROOT . '/attachment/unionpay/encrypt.cer');
define('SDK_VERIFY_CERT_DIR', IA_ROOT . '/attachment/unionpay/');
define('SDK_VERIFY_CERT_PATH', IA_ROOT . '/attachment/unionpay/verify_sign_acp.cer');
define('SDK_MERID', $payment['merid']);

include_once IA_ROOT . '/payment/unionpay/upacp_sdk_php/utf8/func/common.php';
include_once IA_ROOT . '/payment/unionpay/upacp_sdk_php/utf8/func/secureUtil.php';
include_once IA_ROOT . '/payment/unionpay/upacp_sdk_php/utf8/func/PublicEncrypte.php';
include_once IA_ROOT . '/payment/unionpay/upacp_sdk_php/utf8/func/PinBlock.php';