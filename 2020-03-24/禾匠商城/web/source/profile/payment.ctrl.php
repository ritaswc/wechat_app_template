<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('payment');
load()->model('account');
load()->func('communication');

permission_check_account_user('profile_payment_pay');

$dos = array('save_setting', 'save_setting_webapp', 'display', 'test_alipay', 'test_wechat', 'get_setting', 'switch', 'change_status', 'get_account_wechatpay_proxy');
$do = in_array($do, $dos) ? $do : 'display';
$account_sign = $_W['account']['type_sign'];

if ('get_setting' == $do) {
	$pay_setting = payment_setting();
	iajax(0, $pay_setting, '');
}

if ('test_alipay' == $do) {
	$alipay = $_GPC['param'];
	$pay_data = array(
		'uniacid' => $_W['uniacid'],
		'acid' => $_W['acid'],
		'uniontid' => date('Ymd', time()) . time(),
		'module' => 'system',
		'fee' => '0.01',
		'status' => 0,
		'card_fee' => 0.01,
	);
	$params = array();
	$params['tid'] = md5(uniqid());
	$params['user'] = '测试用户';
	$params['fee'] = '0.01';
	$params['title'] = '测试支付接口';
	$params['uniontid'] = $pay_data['uniontid'];
	if ($account_sign == WEBAPP_TYPE_SIGN) {
		$params['service'] = 'create_direct_pay_by_user';
	}
	$result = alipay_build($params, $alipay);
	iajax(0, $result['url'], '');
}

if ('test_wechat' == $do) {
	$wechat = safe_gpc_array($_GPC['param']);
	$param = array(
		'pay_way' => 'web',
		'title' => '测试商品标题',
		'uniontid' => md5(uniqid()),
		'fee' => '0.01',
		'goodsid' => '1',
	);
	$wechat_result = wechat_build($param, $wechat);

	if (is_error($wechat_result)) {
		iajax(1, $wechat_result['message']);
	} else {
		load()->library('qrcode');
		$picture_attach = 'wechat_pay_test_' . $_W['uniacid'] . '.png';
		file_delete($picture_attach);
		QRcode::png($wechat_result['code_url'], ATTACHMENT_ROOT . $picture_attach);
		iajax(0, $_W['siteroot'] . 'attachment/' . $picture_attach);
	}
}

if ('save_setting' == $do) {
	$type = $_GPC['type'];
	$param = $_GPC['param'];
	$setting = uni_setting_load('payment', $_W['uniacid']);
	$pay_setting = empty($setting['payment']) ? array() : $setting['payment'];
	
		if ('wechat_facilitator' == $type) {
			$param['switch'] = 'true' == $param['switch'] ? true : false;
		}
	
	if ('wechat' == $type) {
		$param['account'] = $_W['acid'];
		if (1 == $param['switch']) {
			$param['signkey'] = 2 == $param['version'] ? trim($param['apikey']) : trim($param['signkey']);
		}
	}

	if ('unionpay' == $type) {
		$unionpay = $_GPC['unionpay'];
		$switch_status = ($unionpay['pay_switch'] || $unionpay['recharge_switch']) ? true : false;
		if ($switch_status && empty($_FILES['unionpay']['tmp_name']['signcertpath']) && !file_exists(IA_ROOT . '/attachment/unionpay/PM_' . $_W['uniacid'] . '_acp.pfx')) {
			itoast('请上联银商户私钥证书.', referer(), 'error');
		}
		$param = array(
			'merid' => $unionpay['merid'],
			'signcertpwd' => $unionpay['signcertpwd'],
		);
		if ($switch_status && (empty($param['merid']) || empty($param['signcertpwd']))) {
			itoast('请输入完整的银联支付接口信息.', referer(), 'error');
		}
		if ($switch_status && empty($_FILES['unionpay']['tmp_name']['signcertpath']) && !file_exists(IA_ROOT . '/attachment/unionpay/PM_' . $_W['uniacid'] . '_acp.pfx')) {
			itoast('请上传银联商户私钥证书.', referer(), 'error');
		}
		if ($switch_status && !empty($_FILES['unionpay']['tmp_name']['signcertpath'])) {
			load()->func('file');
			mkdirs(IA_ROOT . '/attachment/unionpay/');
			file_put_contents(IA_ROOT . '/attachment/unionpay/PM_' . $_W['uniacid'] . '_acp.pfx', file_get_contents($_FILES['unionpay']['tmp_name']['signcertpath']));
			$public_rsa = '-----BEGIN CERTIFICATE-----
MIIEIDCCAwigAwIBAgIFEDRVM3AwDQYJKoZIhvcNAQEFBQAwITELMAkGA1UEBhMC
Q04xEjAQBgNVBAoTCUNGQ0EgT0NBMTAeFw0xNTEwMjcwOTA2MjlaFw0yMDEwMjIw
OTU4MjJaMIGWMQswCQYDVQQGEwJjbjESMBAGA1UEChMJQ0ZDQSBPQ0ExMRYwFAYD
VQQLEw1Mb2NhbCBSQSBPQ0ExMRQwEgYDVQQLEwtFbnRlcnByaXNlczFFMEMGA1UE
Aww8MDQxQDgzMTAwMDAwMDAwODMwNDBA5Lit5Zu96ZO26IGU6IKh5Lu95pyJ6ZmQ
5YWs5Y+4QDAwMDE2NDkzMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA
tXclo3H4pB+Wi4wSd0DGwnyZWni7+22Tkk6lbXQErMNHPk84c8DnjT8CW8jIfv3z
d5NBpvG3O3jQ/YHFlad39DdgUvqDd0WY8/C4Lf2xyo0+gQRZckMKEAId8Fl6/rPN
HsbPRGNIZgE6AByvCRbriiFNFtuXzP4ogG7vilqBckGWfAYaJ5zJpaGlMBOW1Ti3
MVjKg5x8t1/oFBkpFVsBnAeSGPJYrBn0irfnXDhOz7hcIWPbNDoq2bJ9VwbkKhJq
Vz7j7116pziUcLSFJasnWMnp8CrISj52cXzS/Y1kuaIMPP/1B0pcjVqMNJjowooD
OxID3TZGfk5V7S++4FowVwIDAQABo4HoMIHlMB8GA1UdIwQYMBaAFNHb6YiC5d0a
j0yqAIy+fPKrG/bZMEgGA1UdIARBMD8wPQYIYIEchu8qAQEwMTAvBggrBgEFBQcC
ARYjaHR0cDovL3d3dy5jZmNhLmNvbS5jbi91cy91cy0xNC5odG0wNwYDVR0fBDAw
LjAsoCqgKIYmaHR0cDovL2NybC5jZmNhLmNvbS5jbi9SU0EvY3JsMjI3Mi5jcmww
CwYDVR0PBAQDAgPoMB0GA1UdDgQWBBTEIzenf3VR6CZRS61ARrWMto0GODATBgNV
HSUEDDAKBggrBgEFBQcDAjANBgkqhkiG9w0BAQUFAAOCAQEAHMgTi+4Y9g0yvsUA
p7MkdnPtWLS6XwL3IQuXoPInmBSbg2NP8jNhlq8tGL/WJXjycme/8BKu+Hht6lgN
Zhv9STnA59UFo9vxwSQy88bbyui5fKXVliZEiTUhjKM6SOod2Pnp5oWMVjLxujkk
WKjSakPvV6N6H66xhJSCk+Ref59HuFZY4/LqyZysiMua4qyYfEfdKk5h27+z1MWy
nadnxA5QexHHck9Y4ZyisbUubW7wTaaWFd+cZ3P/zmIUskE/dAG0/HEvmOR6CGlM
55BFCVmJEufHtike3shu7lZGVm2adKNFFTqLoEFkfBO6Y/N6ViraBilcXjmWBJNE
MFF/yA==
-----END CERTIFICATE-----';
			file_put_contents(IA_ROOT . '/attachment/unionpay/UpopRsaCert.cer', trim($public_rsa));
		}
	}
	$pay_setting[$type] = $param;
	$payment = iserializer($pay_setting);
	uni_setting_save('payment', $payment);
	
		if ('wechat_facilitator' == $type) {
			cache_clean(cache_system_key('proxy_wechatpay_account:'));
		}
	
	iajax(0, '设置成功！', referer());
}

if ('change_status' == $do) {
	$types = array('unionpay', 'jueqiymf', 'alipay', 'baifubao', 'line', 'credit', 'delivery', 'mix', 'wechat');
	$type = in_array($_GPC['type'], $types) ? $_GPC['type'] : '';
	if (empty($type)) {
		iajax(-1, '参数错误！');
	}
	$param = $_GPC['param'];
	$setting = uni_setting_load('payment', $_W['uniacid']);
	$pay_setting = $setting['payment'];
	$setting_data = array(
		'pay_switch' => 'true' == $param['pay_switch'] ? true : false,
		'recharge_switch' => 'true' == $param['recharge_switch'] ? true : false,
	);
	if ('credit' == $type || 'delivery' == $type || 'mix' == $type) {
		$param['recharge_switch'] = false;
	}
	$pay_setting[$type]['pay_switch'] = $setting_data['pay_switch'];
	$pay_setting[$type]['recharge_switch'] = $setting_data['recharge_switch'];
	$payment = iserializer($pay_setting);
	uni_setting_save('payment', $payment);
	iajax(0, '设置成功！', referer());
}

if ('save_setting_webapp' == $do) {
	$type = safe_gpc_string($_GPC['type']);
	$param = safe_gpc_array($_GPC['param']);
	$payment = uni_setting_load('payment');
	$payment = empty($payment['payment']) ? array() : $payment['payment'];
	if ('wechat' == $type) {
		$payment[$type] = array(
			'appid' => $param['appid'],
			'mchid' => $param['mchid'],
			'signkey' => $param['signkey'],
			'version' => 2,
		);
	} elseif ('alipay' == $type) {
		$payment[$type] = array(
			'account' => $param['account'],
			'partner' => $param['partner'],
			'secret' => $param['secret'],
		);
	}
	foreach ($payment[$type] as $item) {
		if (empty($item)) {
			iajax(-1, '缺少必要参数');
		}
	}
	uni_setting_save('payment', $payment);
	iajax(0, '操作成功', url('profile/payment'));
}

if ('display' == $do || 'switch' == $do) {
	if ($account_sign == WEBAPP_TYPE_SIGN) {
		$pay_setting = uni_setting_load('payment');
		$pay_setting = empty($pay_setting['payment']) ? array() : $pay_setting['payment'];
		template('profile/payment_webapp');
		exit;
	}
	$pay_setting = payment_setting();
	$account = array_elements(array('name', 'key', 'secret', 'level'), $_W['account']);
}
if ('switch' == $do) {
	$payment_types = payment_types();
	if (empty($payment_types[$_GPC['type']])) {
		itoast('参数错误', url('profile/payment'), 'error');
	}
}

if ('get_account_wechatpay_proxy' == $do) {
	$proxy_wechatpay_account = account_wechatpay_proxy();
	iajax(0, $proxy_wechatpay_account);
}
template('profile/payment');