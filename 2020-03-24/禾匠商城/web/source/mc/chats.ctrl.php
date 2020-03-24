<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('mc');
load()->classs('wesession');
load()->classs('account');
load()->model('material');

$dos = array('chats', 'send', 'endchats');
$do = in_array($do, $dos) ? $do : 'chats';
permission_check_account_user('mc_fans');

if ('chats' == $do) {
	$account_api = WeAccount::createByUniacid();
	$supports = $account_api->getMaterialSupport();
	$show_chast_content = $supports['chats'];

	$openid = addslashes($_GPC['openid']);
	$fans_info = mc_fansinfo($openid);
	if (!empty($fans_info['uid'])) {
		$fans_info['member_info'] = mc_fetch($fans_info['uid']);
	}
	$chat_record = pdo_getslice('mc_chats_record', array('uniacid' => $_W['uniacid'], 'openid' => $openid), array('1', 20), $total, array(), '', 'createtime desc');
	$chat_record = mc_fans_chats_record_formate($chat_record);
}

if ('send' == $do) {
	$content_formate = mc_send_content_formate($_GPC);
	$send = $content_formate['send'];
	$content = $content_formate['content'];

	$account_api = WeAccount::createByUniacid();
	$result = $account_api->sendCustomNotice($send);
	if (is_error($result)) {
		iajax(-1, $result['message']);
	} else {
				$account = uni_fetch($_W['uniacid']);
		$message['from'] = $_W['openid'] = $send['touser'];
		$message['to'] = $account['original'];
		if (!empty($message['to'])) {
			$sessionid = md5($message['from'] . $message['to'] . $_W['uniacid']);
			session_id($sessionid);
			WeSession::start($_W['uniacid'], $_W['openid'], 300);
			$processor = WeUtility::createModuleProcessor('chats');
			$processor->begin(300);
		}

		if ('mpnews' == $send['msgtype']) {
			$material = table('wechat_attachment')
				->where(array(
					'uniacid' => $_W['uniacid'],
					'media_id' => $content['mediaid']
				))
				->getcolumn('id');
			$content = $content['thumb'];
		}
				table('mc_chats_record')
			->fill(array(
				'uniacid' => $_W['uniacid'],
				'acid' => $acid,
				'flag' => 1,
				'openid' => $send['touser'],
				'msgtype' => $send['msgtype'],
				'content' => iserializer($send[$send['msgtype']]),
				'createtime' => TIMESTAMP,
			))
			->save();
		iajax(0, array('createtime' => date('Y-m-d H:i:s', time()), 'content' => $content, 'msgtype' => $send['msgtype']), '');
	}
}

if ('endchats' == $do) {
	$openid = trim($_GPC['openid']);
	if (empty($openid)) {
		iajax(1, '粉丝openid不合法', '');
	}
	$fans_info = mc_fansinfo($openid);
	$account = uni_fetch($fans_info['uniacid']);
	$message['from'] = $_W['openid'] = $openid['openid'];
	$message['to'] = $account['original'];
	if (!empty($message['to'])) {
		$sessionid = md5($message['from'] . $message['to'] . $_W['uniacid']);
		session_id($sessionid);
		WeSession::start($_W['uniacid'], $_W['openid'], 300);
		$processor = WeUtility::createModuleProcessor('chats');
		$processor->end();
	}
	if (is_error($result)) {
		iajax(1, $result);
	}
}
template('mc/chats');
