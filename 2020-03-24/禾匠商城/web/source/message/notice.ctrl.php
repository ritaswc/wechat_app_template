<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('message');

$dos = array('display', 'change_read_status', 'event_notice', 'all_read', 'setting', 'read', 'wechat_setting');
$do = in_array($do, $dos) ? $do : 'display';

if (in_array($do, array('display', 'all_read'))) {
	$type = $types = intval($_GPC['type']);
	if (MESSAGE_ACCOUNT_EXPIRE_TYPE == $type) {
		$types = array(MESSAGE_ACCOUNT_EXPIRE_TYPE, MESSAGE_WECHAT_EXPIRE_TYPE, MESSAGE_WEBAPP_EXPIRE_TYPE);
	}
	if (MESSAGE_ORDER_TYPE == $type) {
		$types = array(MESSAGE_ORDER_TYPE, MESSAGE_ORDER_APPLY_REFUND_TYPE);
	}

	if (empty($type) && (!user_is_founder($_W['uid']) || user_is_vice_founder())) {
		$types = array(MESSAGE_ACCOUNT_EXPIRE_TYPE, MESSAGE_WECHAT_EXPIRE_TYPE, MESSAGE_WEBAPP_EXPIRE_TYPE, MESSAGE_USER_EXPIRE_TYPE, MESSAGE_WXAPP_MODULE_UPGRADE);
	}
}

if ('display' == $do) {
	$message_id = intval($_GPC['message_id']);
	message_notice_read($message_id);

	$pindex = max(intval($_GPC['page']), 1);
	$psize = 10;

	$message_table = table('core_message_notice_log');
	$is_read = !empty($_GPC['is_read']) ? intval($_GPC['is_read']) : '';

	if (!empty($is_read)) {
		$message_table->searchWithIsRead($is_read);
	}

	if (user_is_founder($_W['uid'], true)) {
		if (!empty($types)) {
			$message_table->searchWithType($types);
		} else {
			$message_table->searchWithOutType(array(MESSAGE_USER_EXPIRE_TYPE, MESSAGE_ORDER_WISH_TYPE));
		}
	} else {
		$message_table->searchWithUid($_W['uid']);
		$message_table->searchWithType(array(
			MESSAGE_ACCOUNT_EXPIRE_TYPE,
			MESSAGE_WECHAT_EXPIRE_TYPE,
			MESSAGE_WEBAPP_EXPIRE_TYPE,
			MESSAGE_USER_EXPIRE_TYPE,
			MESSAGE_WXAPP_MODULE_UPGRADE,
			MESSAGE_SYSTEM_UPGRADE,
			MESSAGE_OFFICIAL_DYNAMICS,
			MESSAGE_ORDER_WISH_TYPE,
		));
	}

	$message_table->searchWithPage($pindex, $psize);
	$lists = $message_table->orderby('id', 'DESC')->getall();

	$lists = message_list_detail($lists);

	$total = $message_table->getLastQueryTotal();
	$pager = pagination($total, $pindex, $psize);

	$wechat_setting = setting_load('message_wechat_notice_setting');
	$wechat_setting = $wechat_setting['message_wechat_notice_setting'];
	if (!empty($wechat_setting['uniacid'])) {
		$uni_account = table('account')->getUniAccountByUniacid($wechat_setting['uniacid']);
		$uni_account['qrcode'] = tomedia('qrcode_' . $uni_account['acid'] . '.jpg') . '?time=' . $_W['timestamp'];
	}
}

if ('change_read_status' == $do) {
	$id = $_GPC['id'];
	message_notice_read($id);
	iajax(0, '成功');
}

if ('event_notice' == $do) {
	if (user_is_founder($_W['uid'], true)) {
		message_store_notice();
	}
	$message = message_event_notice_list();
	$cookie_name = $_W['config']['cookie']['pre'] . '__notice';
	if (empty($_COOKIE[$cookie_name]) || $_COOKIE[$cookie_name] < TIMESTAMP) {
		message_account_expire();
		message_notice_worker();
		message_sms_expire_notice();
		message_user_expire_notice();
		message_sms_account_expire_notice();	
		message_sms_api_account_expire_notice();
		message_wxapp_modules_version_upgrade();
	}
	iajax(0, $message);
}

if ('read' == $do) {
	$message_id = pdo_getcolumn('message_notice_log', array('id' => intval($_GPC['id'])), 'id');
	if (!empty($message_id)) {
		pdo_update('message_notice_log', array('is_read' => MESSAGE_READ), array('id' => $message_id));
	}
	iajax(0, '已标记已读');
}

if ('all_read' == $do) {
	message_notice_all_read($types);
	if ($_W['isajax']) {
		iajax(0, '全部已读', url('message/notice', array('type' => $type)));
	}
	itoast('', referer());
}

if ('setting' == $do) {
	$setting = message_setting($_W['uid']);
	$notice_setting = $_W['user']['notice_setting'];
	foreach ($setting as &$value) {
		foreach ($value['types'] as $type => $item) {
			$value['types'][$type]['status'] = empty($notice_setting[$type]) ? MESSAGE_ENABLE : $notice_setting[$type];
		}
	}
	if (!user_is_founder($_W['uid'], true)) {
		$founder_notice_setting = table('users')->getNoticeSettingByUid($_W['config']['setting']['founder']);
	}

	$type = intval($_GPC['type']);
	if (!empty($type)) {
		$notice_setting[$type] = empty($notice_setting[$type]) || MESSAGE_ENABLE == $notice_setting[$type] ? MESSAGE_DISABLE : MESSAGE_ENABLE;
		user_update(array('uid' => $_W['uid'], 'notice_setting' => $notice_setting));
		iajax(0, '更新成功', url('message/notice/setting'));
	}
}

if ('wechat_setting' == $do) {
	if (!user_is_founder($_W['uid'], true)) {
		itoast('无权限', referer(), 'error');
	}
	$uniacid = intval($_GPC['uniacid']);
	$template = safe_gpc_array($_GPC['tpl']);
	$wechat_setting = setting_load('message_wechat_notice_setting');
	$wechat_setting = $wechat_setting['message_wechat_notice_setting'];
	if (!empty($_GPC['delete'])) {
		$wechat_setting = array(
			'uniacid' => 0,
			'template' => array('order' => '', 'expire' => '', 'register' => '', 'work' => '', 'upgrade' => '', 'message' => ''),
		);
		setting_save($wechat_setting, 'message_wechat_notice_setting');
		itoast('', referer(), 'success');
	}
	if (!empty($uniacid)) {
		$wechat_setting = array(
			'uniacid' => $uniacid,
			'template' => array('order' => '', 'expire' => '', 'register' => '', 'work' => '', 'upgrade' => '', 'message' => ''),
		);
		setting_save($wechat_setting, 'message_wechat_notice_setting');
		iajax(0, '操作成功');
	}
	if (!empty($template)) {
		foreach ($template as $type => $tpl) {
			if (!empty($tpl['tpl']) && !preg_match('/^[a-zA-Z0-9_-]{43}$/', $tpl['tpl'])) {
				$error = $tpl['name'];
				break;
			}
			$wechat_setting['template'][$type] = $tpl['tpl'];
		}
		setting_save($wechat_setting, 'message_wechat_notice_setting');
		if (empty($error)) {
			iajax(0, '');
		} else {
			iajax(1, $error);
		}
	}
	if (!empty($wechat_setting['uniacid'])) {
		$uni_account = uni_fetch($wechat_setting['uniacid']);
		$account = $uni_account->account;
		$account['logo'] = $uni_account->logo;
		$account['switchurl'] = $uni_account->switchurl;
	}
	if (!empty($wechat_setting['uniacid'])) {
		$tpl_list = array(
			'order' => array(
				'tpl' => $wechat_setting['template']['order'],
				'name' => '新订单提醒',
				'help' => '请在微信公众平台选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”新订单提醒“，编号为：“OPENTM400045127”的模板。',
			),
			'order_pay' => array(
				'tpl' => $wechat_setting['template']['order_pay'],
				'name' => '订单支付成功通知',
				'help' => '请在微信公众平台选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”支付成功提醒“，编号为：“OPENTM409997121”的模板。',
			),
			'expire' => array(
				'tpl' => $wechat_setting['template']['expire'],
				'name' => '账号到期消息',
				'help' => '请在微信公众平台选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”服务到期提醒“，编号为：“OPENTM408237933”的模板。',
			),
			'register' => array(
				'tpl' => $wechat_setting['template']['register'],
				'name' => '注册提醒',
				'help' => '请在微信公众平台选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”注册成功通知“，编号为：“OPENTM409879450”的模板。',
			),
			'work_order' => array(
				'tpl' => $wechat_setting['template']['work_order'],
				'name' => '工单提醒',
				'help' => '请在微信公众平台选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”新工单提醒“，编号为：“OPENTM407358460”的模板。',
			),
		);
	}
	$accounts = uni_user_accounts($_W['uid'], 'account');
	foreach ($accounts as $k => $item) {
		if (ACCOUNT_SERVICE_VERIFY != $item['level']) {
			unset($accounts[$k]);
		} else {
			$accounts[$k]['logo'] = is_file(IA_ROOT . '/attachment/headimg_' . $item['acid'] . '.jpg') ? tomedia('headimg_' . $item['acid'] . '.jpg') . '?time=' . time() : './resource/images/nopic-107.png';
		}
	}
}
template('message/notice');
