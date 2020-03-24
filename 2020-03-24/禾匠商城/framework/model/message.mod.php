<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


function message_notice_read($id) {
	$id = intval($id);
	if (empty($id)) {
		return true;
	}
	table('core_message_notice_log')->fillIsRead(MESSAGE_READ)->whereId($id)->save();
	return true;
}


function message_notice_all_read($type = '') {
	global $_W;
	$message_table = table('core_message_notice_log');
	if (!empty($type)) {
		$message_table->whereType($type);
	}
	if (user_is_founder($_W['uid']) && !user_is_vice_founder($_W['uid'])) {
		$message_table->fillIsRead(MESSAGE_READ)->whereIsRead(MESSAGE_NOREAD)->save();
		return true;
	}
	$message_table->fillIsRead(MESSAGE_READ)->whereIsRead(MESSAGE_NOREAD)->whereUid($_W['uid'])->save();
	return true;
}


function message_setting($uid, $type = 0, $params = array()) {
	global $_W;
	$data = array(
		'order_message'	=> array(
			'title' => '订单消息',
			'msg' => '用户购买模块，服务等，提交订单或付款后，将会有消息提醒，建议打开',
			'permission' => array('founder'),
			'types' => array(
				MESSAGE_ORDER_TYPE => array(
					'title' => '提交订单',
					'msg' => '用户购买模块，服务等，提交订单后，将会有消息提醒，建议打开',
					'permission' => array('founder'),
					'notice_data' => array(
						'sign' => $params['orderid'],
						'message' => sprintf(
							'%s ' . date('Y-m-d H:i:s') . ' 在商城订购了%s, 商品金额 %.2f元',
							$params['username'],
							$params['goods_name'],
							$params['money']
						)
					),
				),
				MESSAGE_ORDER_WISH_TYPE => array(
					'title' => '提交星愿订单',
					'msg' => '用户购买星愿应用，提交订单后，将会有消息提醒，建议打开',
					'permission' => array(),
					'notice_data' => array(
						'sign' => $params['orderid'],
						'message' => sprintf(
							'您在商城为%s订购了%s星愿应用, 商品金额 %.2f元',
							$params['account_name'],
							$params['goods_name'],
							$params['money']
						)
					),
				),
				MESSAGE_ORDER_PAY_TYPE => array(
					'title' => '支付成功',
					'msg' => '用户购买模块，服务等，付款后，将会有消息提醒，建议打开',
					'permission' => array('founder'),
					'notice_data' => array(
						'sign' => $params['orderid'],
						'message' => sprintf(
							'%s ' . date('Y-m-d H:i:s') . ' 在商城成功支付 %.2f元',
							$params['username'],
							$params['money']
						)
					),
				),
				MESSAGE_ORDER_APPLY_REFUND_TYPE => array(
					'title' => '申请退款',
					'msg' => '用户购买模块，服务等，付款后，将会有消息提醒，建议打开',
					'permission' => array('founder'),
					'notice_data' => array(
						'sign' => $params['orderid'],
						'message' => sprintf(
							'%s ' . date('Y-m-d H:i:s') . ' 在商城申请退款 %.2f元',
							$params['username'],
							$params['money']
						)
					),
				),
			)
		),
		'expire_message' => array(
			'title' => '到期消息',
			'msg' => '用户公众号，小程序到期，平台类型到期，将会有消息提醒，建议打开',
			'permission' => array(),
			'types' => array(
				MESSAGE_ACCOUNT_EXPIRE_TYPE => array(
					'title' => '公众号到期',
					'msg' => '用户公众号到期后，将会有消息提醒，建议打开',
					'permission' => array(),
					'notice_data' => array(
						'sign' => $params['uniacid'],
						'end_time' => $params['end_time'],
						'message' => sprintf('%s-%s已过期', $params['account_name'], $params['type_name'])
					),
				),
				MESSAGE_WECHAT_EXPIRE_TYPE => array(
					'title' => '小程序到期',
					'msg' => '用户小程序到期后，将会有消息提醒，建议打开',
					'permission' => array(),
					'notice_data' => array(
						'sign' => $params['uniacid'],
						'end_time' => $params['end_time'],
						'message' => sprintf('%s-%s已过期', $params['account_name'], $params['type_name'])
					),
				),
				MESSAGE_WEBAPP_EXPIRE_TYPE => array(
					'title' => 'pc过期',
					'msg' => '用户pc类型到期后，将会有消息提醒，建议打开',
					'permission' => array(),
					'notice_data' => array(
						'sign' => $params['uniacid'],
						'end_time' => $params['end_time'],
						'message' => sprintf('%s-%s已过期', $params['account_name'], $params['type_name'])
					),
				),
				MESSAGE_USER_EXPIRE_TYPE => array(
					'title' => '用户账号到期',
					'msg' => '用户账号到期后，将会有消息提醒，建议打开',
					'permission' => array(),
					'notice_data' => array(
						'sign' => $params['uid'],
						'end_time' => $params['end_time'],
						'message' => sprintf('%s 用户账号即将过期', $params['username'])
					),
				),
			)
		),
		'work_message' => array(
			'title' => '工单提醒',
			'msg' => '站点有工单消息时，将会有消息提醒，建议打开',
			'permission' => array('founder'),
			'types' => array(
				MESSAGE_WORKORDER_TYPE => array(
					'title' => '新工单',
					'msg' => '站点有新工时，将会有消息提醒，建议打开',
					'permission' => array('founder'),
					'notice_data' => array(
						'sign' => $params['uuid'],
						'create_time' => $params['updated_at'],
						'message' => $params['note']
					),
				),
			)
		),
		'register_message' => array(
			'title' => '注册提醒',
			'msg' => '用户注册后，将会有消息提醒，建议打开',
			'permission' => array('founder'),
			'types' => array(
				MESSAGE_REGISTER_TYPE => array(
					'title' => '新用户注册',
					'msg' => '新用户注册后，将会有消息提醒，建议打开',
					'permission' => array('founder'),
					'notice_data' => array(
						'sign' => $params['uid'],
						'status' => $params['status'],
						'message' => sprintf('%s-%s %s注册成功--%s', $params['username'], $params['type_name'], date("Y-m-d H:i:s"), $params['source'])
					),
				),
			),
		),
		'upgrade_message'  => array(
			'title' => '升级提醒',
			'msg' => '账号内应用有升级时,将通知账号主管理员，建议打开',
			'permission' => array('founder'),
			'types' => array(
				MESSAGE_WXAPP_MODULE_UPGRADE => array(
					'title' => '小程序应用升级',
					'msg' => '小程序的应用有升级时,将通知账号主管理员，建议打开',
					'permission' => array('founder'),
					'notice_data' => array(
						'sign' => $params['uniacid'],
						'message' => sprintf('%s小程序中的%s应用有更新', $params['account_name'], $params['module_name'])
					),
				)
			)
		),
	);
	if (empty($type)) {
		return $data;
	}
	foreach ($data as $item) {
		foreach ($item['types'] as $key => $row) {
			$types[$key] = $row;
		}
	}
	if (!is_numeric($type) || !in_array($type, array_keys($types))) {
		return error(1, '消息类型有误');
	}
	$users_table = table('users');
	$founder_notice_setting = $users_table->getNoticeSettingByUid($_W['config']['setting']['founder']);
	if (!empty($founder_notice_setting[$type]) && $founder_notice_setting[$type] == MESSAGE_DISABLE) {
		return error(2, '创始人未开启提醒');
	}
	if (!user_is_founder($uid, true)) {
		$user_notice_setting = $users_table->getNoticeSettingByUid($uid);
		if (!empty($user_notice_setting[$type]) && $user_notice_setting[$type] == MESSAGE_DISABLE) {
			return error(3, '用户未开启提醒');
		}
	}
	$notice_data = $types[$type]['notice_data'];
	$notice_data['uid'] = $uid;
	$notice_data['type'] = $type;
	$notice_data['url'] = '';
	return $notice_data;
}


function message_notice_record($uid, $type, $params) {
	$notice_info = message_setting($uid, $type, $params);
	if (is_error($notice_info)) {
		return $notice_info;
	}
	$message_validate_exists = message_validate_exists($notice_info);
	if (!empty($message_validate_exists)) {
		return true;
	}
	$notice_info['create_time'] = empty($notice_info['create_time']) ? TIMESTAMP : $notice_info['create_time'];
	$notice_info['is_read'] = empty($notice_info['is_read']) ? MESSAGE_NOREAD : $notice_info['is_read'];

	if (in_array($notice_info['type'], array(MESSAGE_ORDER_TYPE, MESSAGE_WORKORDER_TYPE, MESSAGE_REGISTER_TYPE))) {
		message_notice_record_cloud($notice_info);
	}
	table('core_message_notice_log')->fill($notice_info)->save();

	message_send_wechat_notice($notice_info);
	return true;
}

function message_send_wechat_notice($notice_info) {
	global $_W;
	$setting = setting_load('message_wechat_notice_setting');
	$setting = $setting['message_wechat_notice_setting'];
	if (empty($setting['uniacid'])) {
		return error(-1, '未设置公众号');
	}
	$uniaccount = table('account')->getUniAccountByUniacid($setting['uniacid']);
	if (empty($uniaccount)) {
		return error(-1, '帐号不存在或是已经被删除');
	}
	$account_api = WeAccount::createByUniacid($uniaccount['uniacid']);
	if (is_error($account_api)) {
		return $account_api;
	}
	$type_template = array(
		MESSAGE_ORDER_TYPE => 'order',
		MESSAGE_ORDER_PAY_TYPE => 'order_pay',
		MESSAGE_ACCOUNT_EXPIRE_TYPE => 'expire',
		MESSAGE_WECHAT_EXPIRE_TYPE => 'expire',
		MESSAGE_WEBAPP_EXPIRE_TYPE => 'expire',
		MESSAGE_USER_EXPIRE_TYPE => 'expire',
		MESSAGE_WORKORDER_TYPE => 'work_order',
		MESSAGE_REGISTER_TYPE => 'register',
		MESSAGE_WXAPP_MODULE_UPGRADE => '',
		MESSAGE_SYSTEM_UPGRADE => '',
		MESSAGE_OFFICIAL_DYNAMICS => '',
	);
	if (empty($setting['template'][$type_template[$notice_info['type']]])) {
		return error(-1, '未设置模板ID');
	}
	if ($type_template[$notice_info['type']] == 'expire' && user_is_founder($notice_info['uid'], true)) {
		return error(-1, '主管理员不发送过期消息');
	}
	if ($notice_info['type'] == MESSAGE_REGISTER_TYPE) {
		$notice_info['uid'] = $_W['config']['setting']['founder'];
	}
	$users_bind = table('users_bind')->getByTypeAndUid(USER_REGISTER_TYPE_OPEN_WECHAT, $notice_info['uid']);
	if (empty($users_bind['bind_sign'])) {
		return error(-1, '用户未绑定微信');
	}
	$mc_mapping_fans_table = table('mc_mapping_fans');
	$mc_mapping_fans_table->searchWithUniacid($setting['uniacid']);
	$mc_mapping_fans_table->searchWithUnionid($users_bind['bind_sign']);
	$fans = $mc_mapping_fans_table->get();
	if (empty($fans['openid'])) {
		return error(-1, '用户未关注公众号');
	}
	$msg_data = array();
	switch ($notice_info['type']) {
		case MESSAGE_ORDER_TYPE:
			$order = pdo_get('site_store_order', array('id' => $notice_info['sign']));
			$msg_data = array(
				'first' => array('value' => '您好，您的商城有新的订单！'),
				'keyword1' => array('value' => $order['orderid']),
				'keyword2' => array('value' => date('Y年m月d日 H:i')),
				'remark' => array('value' => $notice_info['message']),
			);
			break;
		case MESSAGE_ORDER_PAY_TYPE:
			$order = pdo_get('site_store_order', array('id' => $notice_info['sign']));
			$msg_data = array(
				'first' => array('value' => '您好，您已经成功付款！'),
				'keyword1' => array('value' => '商城购买商品'),
				'keyword2' => array('value' => $order['amount']),
				'keyword3' => array('value' => date('Y年m月d日 H:i')),
				'remark' => array('value' => '感谢您的使用！'),
			);
			break;
		case MESSAGE_ACCOUNT_EXPIRE_TYPE:
			$time = empty($notice_info['end_time']) ? TIMESTAMP : $notice_info['end_time'];
			$msg_data = array(
				'first' => array('value' => '您好，您有过期的账号！'),
				'keyword1' => array('value' => $notice_info['message']),
				'keyword2' => array('value' => '公众号'),
				'keyword3' => array('value' => date('Y年m月d日 H:i', $time)),
				'remark' => array('value' => '感谢您的使用！'),
			);
			break;
		case MESSAGE_WECHAT_EXPIRE_TYPE:
			$time = empty($notice_info['end_time']) ? TIMESTAMP : $notice_info['end_time'];
			$msg_data = array(
				'first' => array('value' => '您好，您有过期的账号！'),
				'keyword1' => array('value' => $notice_info['message']),
				'keyword2' => array('value' => '小程序'),
				'keyword3' => array('value' => date('Y年m月d日 H:i', $time)),
				'remark' => array('value' => '感谢您的使用！'),
			);
			break;
		case MESSAGE_WEBAPP_EXPIRE_TYPE:
			$time = empty($notice_info['end_time']) ? TIMESTAMP : $notice_info['end_time'];
			$msg_data = array(
				'first' => array('value' => '您好，您有过期的账号！'),
				'keyword1' => array('value' => $notice_info['message']),
				'keyword2' => array('value' => 'PC'),
				'keyword3' => array('value' => date('Y年m月d日 H:i', $time)),
				'remark' => array('value' => '感谢您的使用！'),
			);
			break;
		case MESSAGE_USER_EXPIRE_TYPE:
			$msg_data = array(
				'first' => array('value' => '您好，您的账号即将过期！'),
				'keyword1' => array('value' => $_W['user']['username']),
				'keyword2' => array('value' => '用户账号'),
				'keyword3' => array('value' => date('Y年m月d日 H:i', $_W['user']['endtime'])),
				'remark' => array('value' => '感谢您的使用！'),
			);
			break;
		case MESSAGE_WORKORDER_TYPE:
			$time = empty($notice_info['create_time']) ? TIMESTAMP : $notice_info['create_time'];
			$msg_data = array(
				'first' => array('value' => '您好，您有新的工单提交！！'),
				'keyword1' => array('value' => $notice_info['sign']),
				'keyword2' => array('value' => $notice_info['message']),
				'keyword3' => array('value' => date('Y年m月d日 H:i', $time)),
				'remark' => array('value' => '感谢您的使用！'),
			);
			break;
		case MESSAGE_REGISTER_TYPE:
			$source = substr($notice_info['message'], stripos($notice_info['message'], '--')+2);
			$source_array = array('mobile' => '手动注册', 'system' => '手动注册', 'qq' => 'QQ 注册', 'wechat' => '微信注册', 'admin' => '管理员添加');
			$user = pdo_get('users', array('uid' => $notice_info['sign']));
			$msg_data = array(
				'first' => array('value' => '您好，有新用户在站点注册！'),
				'keyword1' => array('value' => $user['username']),
				'keyword2' => array('value' => date('Y年m月d日 H:i')),
				'keyword3' => array('value' => $source_array[$source]),
				'remark' => array('value' => '感谢您的使用！'),
			);
			break;
		case MESSAGE_WXAPP_MODULE_UPGRADE:
		case MESSAGE_SYSTEM_UPGRADE:
		case MESSAGE_OFFICIAL_DYNAMICS:
			break;
	}
	return $account_api->sendTplNotice($fans['openid'], $setting['template'][$type_template[$notice_info['type']]], $msg_data);
}


function message_validate_exists($message) {
	$message_exists = table('core_message_notice_log')->messageExists($message);
	if (!empty($message_exists)) {
		return true;
	}
	return false;
}


function message_event_notice_list() {
	load()->model('user');
	global $_W;
	$message_table = table('core_message_notice_log');
	$message_table->searchWithIsRead(MESSAGE_NOREAD);
	if (user_is_founder($_W['uid'], true)) {
		$message_table->searchWithOutType(MESSAGE_USER_EXPIRE_TYPE);
	} else {
		$message_table->searchWithUid($_W['uid']);
		$message_table->searchWithType(array(
			MESSAGE_ACCOUNT_EXPIRE_TYPE,
			MESSAGE_WECHAT_EXPIRE_TYPE,
			MESSAGE_WEBAPP_EXPIRE_TYPE,
			MESSAGE_USER_EXPIRE_TYPE,
			MESSAGE_WXAPP_MODULE_UPGRADE,
			MESSAGE_SYSTEM_UPGRADE,
			MESSAGE_OFFICIAL_DYNAMICS
		));
	}
	$message_table->searchWithPage(1, 10);
	$lists = $message_table->orderby('id', 'DESC')->getall();
	$total = $message_table->getLastQueryTotal();
	$lists = message_list_detail($lists);
	return array(
		'lists' => $lists,
		'total' => $total,
		'more_url' => url('message/notice') . (igetcookie('__iscontroller') ? 'iscontroller=1' : ''),
		'all_read_url' => url('message/notice/all_read') . (igetcookie('__iscontroller') ? 'iscontroller=1' : ''),
	);
}



function message_account_expire() {
	global $_W;
	load()->model('account');
	$account_table = table('account');
	$expire_account_list = $account_table->searchAccountList();
	if (empty($expire_account_list)) {
		return true;
	}
	foreach ($expire_account_list as $account) {
		$account_detail = uni_fetch($account['uniacid']);
		if (empty($account_detail->owner['uid'])) {
			continue;
		}
		if ($account_detail['endtime'] > USER_ENDTIME_GROUP_UNLIMIT_TYPE && $account_detail['endtime'] < TIMESTAMP) {
			switch ($account_detail['type']) {
				case ACCOUNT_TYPE_APP_NORMAL:
					$type = MESSAGE_WECHAT_EXPIRE_TYPE;
					break;
				case ACCOUNT_TYPE_WEBAPP_NORMAL:
					$type = MESSAGE_WEBAPP_EXPIRE_TYPE;
					break;
				default:
					$type = MESSAGE_ACCOUNT_EXPIRE_TYPE;
					break;
			}
			$params = array(
				'uniacid' => $account_detail['uniacid'],
				'end_time' => $account_detail['endtime'],
				'account_name' => $account_detail['name'],
				'type_name' => $account_detail->typeName,
			);
			$result = message_notice_record($account_detail->owner['uid'], $type, $params);
			if (is_error($result) && $result['errno'] == 3) {
				message_notice_record($_W['config']['setting']['founder'], $type, $params);
			}
		}
	}
	return true;
}


function message_notice_worker() {
	global $_W;
	load()->func('communication');
	load()->classs('cloudapi');
	$api = new CloudApi();
	$table = table('core_message_notice_log');
	$time = 0;
	$table->searchWithType(MESSAGE_WORKORDER_TYPE);
	$message_record = $table->orderby('id', 'DESC')->get();

	if (!empty($message_record)) {
		$time = $message_record['create_time'];
	}

	if (!empty($time) && TIMESTAMP - $time < 60 * 60 * 6) {
		return true;
	}

	$api_url = $api->get('system', 'workorder', array('do' => 'notload', 'time' => $time), 'json', false);
	if (is_error($api_url)) {
		return true;
	}

	$request_url = $api_url['data']['url'];
	$response = ihttp_get($request_url);
	$uid = $_W['config']['setting']['founder'];
	if ($response['code'] == 200) {
		$content = $response['content'];
		$worker_notice_lists = json_decode($content, JSON_OBJECT_AS_ARRAY);
		if (!empty($worker_notice_lists)) {
			foreach ($worker_notice_lists as $list) {
				message_notice_record($uid, MESSAGE_WORKORDER_TYPE, array(
					'uuid' => $list['uuid'],
					'note' => $list['note'],
					'updated_at' => $list['updated_at'],
				));
			}
		}
	}
	return true;
}


function message_sms_expire_notice() {
	load()->model('cloud');
	load()->model('setting');
	$setting_user_expire = setting_load('user_expire');
	if (empty($setting_user_expire['user_expire']['status'])) {
		return true;
	}

	$setting_sms_sign = setting_load('site_sms_sign');
	$custom_sign = !empty($setting_sms_sign['site_sms_sign']['user_expire']) ? $setting_sms_sign['site_sms_sign']['user_expire'] : '';

	$day = max(1, intval($setting_user_expire['user_expire']['day']));

	$user_table = table('users');
	$user_table->searchWithMobile();
	$user_table->searchWithEndtime($day);
	$user_table->where('u.endtime >', TIMESTAMP - 86400 * 7); 	$user_table->searchWithSendStatus();
	$user_table->searchWithViceFounder();
	$users_expire = $user_table->getUsersList();

	if (empty($users_expire)) {
		return true;
	}
	foreach ($users_expire as $v) {
		if (empty($v['puid'])) {
			continue;
		}
		if (!empty($v['mobile']) && preg_match(REGULAR_MOBILE, $v['mobile'])) {
			$result = cloud_sms_send($v['mobile'], '800015', array('username' => $v['username']), $custom_sign, true);
			if (is_error($result)) {
				$content = "您的用户名{$v['username']}即将过期。";

				$data = array('mobile' => $v['mobile'], 'content' => $content, 'result' => $result['errno'] . $result['message'], 'createtime' => TIMESTAMP);
				table('core_sendsms_log')->fill($data)->save();
			} else {
				$profile_table = table('users_profile');
				$profile = $profile_table->whereUid($v['uid'])->get();
				if ($profile) {
					$profile_table->whereId($profile['id']);
				}
				$profile_table->fill(array('send_expire_status' => 1,'uid' => $v['uid']))->save();
			}
		}
	}
	return true;
}


function message_user_expire_notice() {
	global $_W;
	if (!empty($_W['user']['endtime']) && $_W['user']['endtime'] < strtotime('+7 days')) {
		$params = array(
			'uid' => $_W['user']['uid'],
			'username' => $_W['user']['username'],
			'end_time' => $_W['user']['endtime'],
		);
		$result = message_notice_record($_W['uid'], MESSAGE_USER_EXPIRE_TYPE, $params);
		if (is_error($result) && $result['errno'] == 3) {
			message_notice_record($_W['config']['setting']['founder'], MESSAGE_USER_EXPIRE_TYPE, $params);
		}
	}
	return true;
}


function message_sms_account_expire_notice() {
	load()->model('cloud');
	load()->model('setting');
	$setting_user_expire = setting_load('account_sms_expire');
	if (empty($setting_user_expire['account_sms_expire']['status'])) {
		return true;
	}

	$setting_sms_sign = setting_load('site_sms_sign');
	$custom_sign = !empty($setting_sms_sign['site_sms_sign']['account_expire']) ? $setting_sms_sign['site_sms_sign']['account_expire'] : '';
	$day = max(1, intval($setting_user_expire['account_sms_expire']['day']));

	$account_expire = table('account')
		->searchWithuniAccountUsers()
		->leftjoin('users_profile', 'c')
		->on(array('b.uid' => 'c.uid'))
		->leftjoin('uni_account', 'd')
		->on('d.uniacid', 'a.uniacid')
		->leftjoin('users', 'e')
		->on('e.uid', 'b.uid')
		->select(array('a.uniacid', 'd.name', 'c.mobile', 'e.username'))
		->where(array(
			'a.endtime >' => TIMESTAMP,
			'a.endtime <' => TIMESTAMP + 86400 * $day,
			'a.isdeleted' => 0,
			'a.send_account_expire_status' => 0,
			'b.role' => 'owner',
			'c.mobile !=' => '',
			'd.name !=' => '',
			'e.endtime >' => TIMESTAMP
		))
		->getall();

	if (empty($account_expire)) {
		return true;
	}
	foreach ($account_expire as $v) {
		if (!empty($v['mobile']) && preg_match(REGULAR_MOBILE, $v['mobile'])) {
			$result = cloud_sms_send($v['mobile'], '800016', array('name' => $v['name']), $custom_sign, true);
			if (is_error($result)) {
				$content = "您的平台账号{$v['name']}即将过期,为了不影响正常使用，请及时续费。";

				$data = array('mobile' => $v['mobile'], 'content' => $content, 'result' => $result['errno'] . $result['message'], 'createtime' => TIMESTAMP);
				table('core_sendsms_log')->fill($data)->save();
			} else {
				$profile_table = table('account');
				$profile = $profile_table->getByUniacid($v['uniacid']);
				if ($profile) {
					$profile_table->where('uniacid', $v['uniacid']);
				}
				$profile_table->fill(array('send_account_expire_status' => 1))->save();
			}
		}
	}
	return true;
}


function message_sms_api_account_expire_notice() {
	load()->model('cloud');
	load()->model('setting');
	$setting_api_expire = setting_load('api_sms_expire');

	if (empty($setting_api_expire['api_sms_expire']['status'])) {
		return true;
	}

	$setting_sms_sign = setting_load('site_sms_sign');
	$custom_sign = !empty($setting_sms_sign['site_sms_sign']['api_expire']) ? $setting_sms_sign['site_sms_sign']['api_expire'] : '';

	$num = max(1, intval($setting_api_expire['api_sms_expire']['num']));

	$account_expire = table('account')
		->searchWithuniAccountUsers()
		->leftjoin('users_profile', 'c')
		->on(array('b.uid' => 'c.uid'))
		->leftjoin('uni_account', 'd')
		->on('d.uniacid', 'a.uniacid')
		->leftjoin('users', 'e')
		->on('e.uid', 'b.uid')
		->select(array('a.uniacid', 'd.name', 'c.mobile'))
		->where(array(
			'a.endtime >' => TIMESTAMP,
			'a.isdeleted' => 0,
			'a.send_api_expire_status' => 0,
			'b.role' => 'owner',
			'c.mobile !=' => '',
			'd.name !=' => '',
			'e.endtime >' => TIMESTAMP
		))
		->getall();

	if (empty($account_expire)) {
		return true;
	}
	foreach ($account_expire as $v) {
		if (!empty($v['mobile']) && preg_match(REGULAR_MOBILE, $v['mobile'])) {
						$statistics_setting = (array) uni_setting_load(array('statistics'), $v['uniacid']);
			$statistics_setting = !empty($statistics_setting['statistics']) ? $statistics_setting['statistics'] : array();
			if (empty($statistics_setting) || empty($statistics_setting['founder'])) {
				continue;
			}

			$highest_api_visit = $statistics_setting['founder'];
			$month_use = 0;

			$stat_visit_teble = table('stat_visit');
			$stat_visit_teble->searchWithGreaterThenDate(date('Ym01'));
			$stat_visit_teble->searchWithLessThenDate(date('Ymt'));
			$stat_visit_teble->searchWithType('app');
			$stat_visit_teble->searchWithUnacid($v['uniacid']);
			$visit_list = $stat_visit_teble->getall();

			if (!empty($visit_list)) {
				foreach ($visit_list as $key => $val) {
					$month_use += $val['count'];
				}
			}

			$order_num = 0;
			$orders = table('site_store_order')->getApiOrderByUniacid($v['uniacid']);
			if (!empty($orders)) {
				foreach ($orders as $order) {
					$order_num += $order['duration'] * $order['api_num'] * 10000;
				}
			}

			$api_remain_num = empty($statistics_setting['use']) ? $highest_api_visit + $order_num : ($highest_api_visit + $order_num - $statistics_setting['use']);
			if ($api_remain_num > $num){
				continue;
			}

			$result = cloud_sms_send($v['mobile'], '800017', array('name' => $v['name']), $custom_sign, true);
			if (is_error($result)) {
				$content = "您的平台账号{$v['name']}剩余的API流量即将耗尽，请及时购买。";

				$data = array('mobile' => $v['mobile'], 'content' => $content, 'result' => $result['errno'] . $result['message'], 'createtime' => TIMESTAMP);
				table('core_sendsms_log')->fill($data)->save();
			} else {
				$profile_table = table('account');
				$profile = $profile_table->getByUniacid($v['uniacid']);
				if ($profile) {
					$profile_table->where('uniacid', $v['uniacid']);
				}
				$profile_table->fill(array('send_api_expire_status' => 1))->save();
			}
		}
	}
	return true;
}



function message_notice_record_cloud($message) {
	load()->classs('cloudapi');
	$api = new CloudApi();
	$result = $api->post('system', 'notify', array('json' => $message), 'html', false);
	return $result;
}


function message_wxapp_modules_version_upgrade() {
	global $_W;
	load()->model('miniapp');
	load()->model('account');

	$wxapp_table = table('account');
	$wxapp_table->searchWithType(array(ACCOUNT_TYPE_APP_NORMAL));
	$uniacid_list = $wxapp_table->searchAccountList();

	if (empty($uniacid_list)) {
		return true;
	}
	$wxapp_list = table('account_wxapp')->wxappInfo(array_keys($uniacid_list));
	$wxapp_modules = table('modules')->getSupportWxappList();

	foreach ($uniacid_list as $uniacid_info) {
		$account_owner = account_owner($uniacid_info['uniacid']);
		if (empty($account_owner) || $account_owner['uid'] != $_W['uid']) {
			continue;
		}

		$uniacid_modules = miniapp_version_all($uniacid_info['uniacid']);

		if (empty($uniacid_modules[0]['modules'])) {
			continue;
		}

		foreach ($uniacid_modules[0]['modules'] as $module) {
			if ($module['version'] < $wxapp_modules[$module['mid']]['version']) {
				message_notice_record($_W['uid'], MESSAGE_WXAPP_MODULE_UPGRADE, array(
					'uniacid' => $uniacid_info['uniacid'],
					'account_name' => $wxapp_list[$uniacid_info['uniacid']]['name'],
					'module_name' => $module['title'],
				));
			}
		}
	}
	return true;
}


function message_list_detail($lists) {
	if (empty($lists)) {
		return $lists;
	}
	foreach ($lists as &$message) {
		$message['create_time'] = date('Y-m-d H:i:s', $message['create_time']);

		if (in_array($message['type'], array(MESSAGE_ORDER_TYPE, MESSAGE_ORDER_WISH_TYPE, MESSAGE_ORDER_PAY_TYPE))) {
			$message['url'] = url('site/entry/orders', array('m' => 'store', 'direct'=>1, 'message_id' => $message['id']));
		}
		if ($message['type'] == MESSAGE_ACCOUNT_EXPIRE_TYPE) {
			$message['url'] = url('account/manage', array('account_type' => ACCOUNT_TYPE_OFFCIAL_NORMAL, 'message_id' => $message['id']));
		}
		if ($message['type'] == MESSAGE_WECHAT_EXPIRE_TYPE) {
			$message['url'] = url('account/manage', array('account_type' => ACCOUNT_TYPE_APP_NORMAL, 'message_id' => $message['id']));
		}
		if ($message['type'] == MESSAGE_WEBAPP_EXPIRE_TYPE) {
			$message['url'] = url('account/manage', array('account_type' => ACCOUNT_TYPE_WEBAPP_NORMAL, 'message_id' => $message['id']));
		}
		if ($message['type'] == MESSAGE_REGISTER_TYPE) {
			if ($message['status'] == USER_STATUS_CHECK) {
				$message['url'] = url('user/display', array('type' => 'check', 'message_id' => $message['id']));
			}
			if ($message['status'] == USER_STATUS_NORMAL) {
				$message['url'] = url('user/display', array('message_id' => $message['id']));
			}
			$source_array = array('mobile' => '手动注册', 'system' => '手动注册', 'qq' => 'QQ 注册', 'wechat' => '微信注册', 'admin' => '管理员添加');
			$msg = explode('--', $message['message']);
			if (count($msg) > 1 && !empty($source_array[$msg[1]])) {
				$message['message'] = $msg[0];
				$message['source'] = $source_array[$msg[1]];
			}
		}
		if ($message['type'] == MESSAGE_USER_EXPIRE_TYPE) {
			$message['url'] = url('user/profile', array('message_id' => $message['id']));
		}
		if ($message['type'] == MESSAGE_WXAPP_MODULE_UPGRADE) {
			$message['url'] = url('message/notice', array('message_id' => $message['id']));
		}
		if ($message['type'] == MESSAGE_WORKORDER_TYPE) {
			$message['url'] = url('system/workorder/display', array('uuid' => $message['sign'], 'message_id' => $message['id']));
		}
	}

	return $lists;
}



function message_store_notice() {
	$cachekey = cache_system_key('cloud_ad_store_notice');
	$cache = cache_load($cachekey);
	if (!empty($cache['expire']) && $cache['expire'] > TIMESTAMP) {
		return true;
	}

	load()->model('cloud');
	$data = cloud_get_store_notice();
	if (is_error($data)) {
		return $data;
	}

	$insert_data = array();
	$signs = array();
	$create_time = array();
	foreach ($data['version'] as $item) {
		$signs[] = $item['itemid'];
		$create_time[] = $item['datetime'];
		$insert_data[] = array(
			'sign' => $item['itemid'],
			'message' => $item['title'],
			'url' => $item['url'],
			'create_time' => $item['datetime'],
			'type' => MESSAGE_SYSTEM_UPGRADE,
			'is_read' => MESSAGE_NOREAD,
		);
	}
	foreach ($data['info'] as $item) {
		$signs[] = $item['itemid'];
		$create_time[] = $item['datetime'];
		$insert_data[] = array(
			'sign' => $item['itemid'],
			'message' => $item['title'],
			'url' => $item['url'],
			'create_time' => $item['datetime'],
			'type' => MESSAGE_OFFICIAL_DYNAMICS,
			'is_read' => MESSAGE_NOREAD,
		);
	}

	if (!empty($signs)) {
		array_multisort($create_time, SORT_ASC, SORT_NUMERIC, $insert_data);

		$signs = pdo_getall('message_notice_log', array('sign' => $signs), array('sign'), 'sign');
		$signs = array_keys($signs);
		foreach ($insert_data as $item) {
			if (!in_array($item['sign'], $signs)) {
				pdo_insert('message_notice_log', $item);
			}
		}
	}
	cache_write($cachekey, array('expire' => TIMESTAMP + 3600));
	return true;
}