<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('setting');
load()->model('utility');

$dos = array('send_code', 'check_smscode');
$do = in_array($do, $dos) ? $do : 'send_code';

$custom_sign = safe_gpc_string($_GPC['custom_sign']);
$_W['uniacid'] = intval($_GPC['uniacid']);
if (empty($_W['uniacid'])) {
	$uniacid_arr = array(
		'name' => '短信验证码',
	);
} else {
	$uniacid_arr = pdo_fetch('SELECT * FROM ' . tablename('uni_account') . ' WHERE uniacid = :uniacid', array(':uniacid' => $_W['uniacid']));
	if (empty($uniacid_arr)) {
		iajax(-1, '非法访问');
	}
}

$receiver = trim($_GPC['receiver']);
if (empty($receiver) && $_W['setting']['copyright']['login_verify_status']) {
	$receiver = $_W['setting']['copyright']['login_verify_mobile'];
}
if (empty($receiver)) {
	iajax(-1, '请输入邮箱或手机号');
} elseif (preg_match(REGULAR_MOBILE, $receiver)) {
	$receiver_type = 'mobile';
} elseif (preg_match("/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $receiver)) {
	$receiver_type = 'email';
} else {
	iajax(-1, '您输入的邮箱或手机号格式错误');
}

pdo_delete('uni_verifycode', array('createtime <' => TIMESTAMP - 1800));

if ('check_smscode' == $do) {
	$smscode = intval($_GPC['smscode']);
	$verify_res = utility_smscode_verify(0, $receiver, $smscode);
	if (is_error($verify_res)) {
		iajax($verify_res['errno'], $verify_res['message']);
	}
}

if ('send_code' == $do) {
	$verifycode_table = table('uni_verifycode');
	$row = $verifycode_table->getByReceiverVerifycode($_W['uniacid'], $receiver, '');

	$record = array();
	$code = random(6, true);

	if (!empty($row)) {
		$imagecode = intval($_GPC['imagecode']);
		$failed_count = table('uni_verifycode')->getFailedCountByReceiver($receiver);

		if ($failed_count >= 3) {
			if (empty($imagecode)) {
				iajax(-3, '请输入图形验证码!');
			}

			if (!checkcaptcha($imagecode)) {
				iajax(-1, '图形验证码错误!');
			}
		}

		if ($row['total'] >= 5) {
			iajax(-1, '您的操作过于频繁,请稍后再试');
		}

		$record['total'] = $row['total'] + 1;
	} else {
		$record['uniacid'] = $_W['uniacid'];
		$record['receiver'] = $receiver;
		$record['total'] = 1;
	}
	$record['verifycode'] = $code;
	$record['createtime'] = TIMESTAMP;

	if (!empty($row)) {
		pdo_update('uni_verifycode', $record, array('id' => $row['id']));
	} else {
		pdo_insert('uni_verifycode', $record);
	}
	if ('email' == $receiver_type) {
		load()->func('communication');
		$content = "您的邮箱验证码为: {$code} 您正在使用{$uniacid_arr['name']}相关功能, 需要你进行身份确认.";
		$result = ihttp_email($receiver, "{$uniacid_arr['name']}身份确认验证码", $content);
	} else {
		load()->model('cloud');
		$r = cloud_prepare();
		if (is_error($r)) {
			iajax(-1, $r['message']);
		}
		$setting = uni_setting($_W['uniacid'], 'notify');
		$postdata = array('verify_code' => $code, 'module' => $uniacid_arr['name']);
		$result = cloud_sms_send($receiver, '800002', $postdata, $custom_sign);
	}
	if (is_error($result)) {
		iajax(-1, $result['message']);
	}
	iajax(0, '短信发送成功!');
}
