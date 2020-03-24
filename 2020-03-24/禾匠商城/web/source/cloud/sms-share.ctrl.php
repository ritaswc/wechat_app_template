<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('cloud');
$dos = array('list', 'delete', 'system', 'add', 'edit', 'display', 'setting_sign', 'change_setting', 'setting_balance');
$do = in_array($do, $dos) ? $do : 'display';
$sms_info = cloud_sms_info();
$sms_count_remained = cloud_sms_count_remained();

if ('list' == $do) {
	if ($_W['isajax']) {
		$status = safe_gpc_int($_GPC['status']);
		$keyword = safe_gpc_string($_GPC['keyword']);
		$type = safe_gpc_string($_GPC['type']);
		$account_types = uni_account_type_sign();
		$pindex = max(1, safe_gpc_int($_GPC['page']));
		$psize = 20;
		$order = array('a.uniacid' => 'DESC');
		$where = array(
			'notify LIKE' => '%sms%'
		);
		if ($type) {
			$where['c.type IN'] = $account_types[$type]['contain_type'];
		}
		if (!empty($keyword)) {
			$where['b.name LIKE'] = "%$keyword%";
		}

		$uni_settings = table('uni_settings');
		$list = $uni_settings
			->searchWirhUniAccountAndAccount()
			->select(array('a.uniacid', 'a.notify', 'b.name', 'c.type'))
			->where($where)
			->orderby($order)
			->getall();
		if (empty($type) && empty($keyword)) {
			$setting_sms_blance = setting_load('system_sms_balance');
			$system_sms_balance = !empty($setting_sms_blance['system_sms_balance']) ? $setting_sms_blance['system_sms_balance'] : 0;
			$setting_sms_sign = setting_load('site_sms_sign');
			$setting_sms_sign = !empty($setting_sms_sign['site_sms_sign']) ? $setting_sms_sign['site_sms_sign'] : array();
			$system_sms_sign = !empty($setting_sms_sign['system_sms_sign']) ? $setting_sms_sign['system_sms_sign'] : '';
			$system = array(
				'uniacid' => 0,
				'notify' => array(
					'sms' => array(
						'balance' => $system_sms_balance,
						'signature' => $system_sms_sign
					),
				),
				'name' => '系统短信',
				'type' => 0
			);
			array_unshift($list, $system);
		}
		$total = count($list);
		if (!empty($list)) {
			foreach($list as &$value){
				$value['notify'] = iunserializer($value['notify']);
			}
		}
		$list = array_slice($list, ($pindex - 1) * $psize, $psize);;
		$message = array(
			'account_types' => $account_types,
			'list' => $list,
			'total' => $total,
			'page' => $pindex,
			'page_size' => $psize,
			'pager' => pagination($total, $pindex, $psize, '', array('before' => '2', 'after' => '3', 'ajaxcallback' => 'null')),
		);
		iajax(0, $message);
	}
}

if ('delete' == $do) {
	$uniacid = safe_gpc_int($_GPC['uniacid']);
	$settings = table('uni_settings')->select(array('uniacid', 'notify'))->where(array('uniacid' => $uniacid))->get();
	$notify = iunserializer($settings['notify']);
	unset($notify['sms']);
	$re = table('uni_settings')
		->where('uniacid', $uniacid)
		->fill(array('notify' => iserializer($notify)))
		->save();
	iajax(0, '操作成功');
}

if ('add' == $do || 'edit' == $do){
	if ($_W['isajax']) {
		$uniacid = safe_gpc_int($_GPC['uniacid']);
		$balance = safe_gpc_int($_GPC['balance']);
		$sign = safe_gpc_string($_GPC['signature']);
		if (($do == 'edit' || $_W['ispost']) && empty($uniacid)) {
			iajax(-1, '参数错误');
		}
		if (!empty($uniacid)) {
			$settings = table('uni_settings')->select(array('uniacid', 'notify'))->where(array('uniacid' => $uniacid))->get();
			$settings['notify'] = iunserializer($settings['notify']);
		}
		if (is_error($sms_info)) {
			iajax(-1, $sms_info['message']);
		}

		if (is_error($sms_count_remained)) {
			iajax(-1, $sms_count_remained['message']);
		}

		if ($_W['ispost']) {
			$notify = $settings['notify'] ? $settings['notify'] : array();
			$signs = $sms_info['sms_sign'];
			if (0 == $sms_count_remained) {
				iajax(-1, '您现有短信数量为0，请联系服务商购买短信！', '');
			}
			if ($balance > $sms_count_remained) {
				iajax(-1, '您设置的短信数量超过了平台当前可用数量：' . $sms_count_remained);
			}
			if (!in_array($sign, $signs)) {
				iajax(-1, '当前签名不可用');
			}
			$notify['sms']['balance'] = max(0, $balance);
			$notify['sms']['signature'] = $sign;
			if (!empty($settings)) {
				$re = table('uni_settings')
					->where('uniacid', $uniacid)
					->fill(array('notify' => iserializer($notify)))
					->save();
			} else {
				$notify['sms']['status'] = 1;
				$re = table('uni_settings')
					->fill(array('notify' => iserializer($notify)))
					->save();
			}
			cache_delete(cache_system_key('uniaccount', array('uniacid' => $uniacid)));
			cache_delete(cache_system_key('cloud_api', array('method' => md5('cloud_sms_count_remained'))));
			if ($re) {
				iajax(0, '设置成功', '');
			} else {
				iajax(1, '修改失败！', '');
			}
		}
		$accounts = table('uni_account')
			->searchWithAccount()
			->where('b.isdeleted', 0)
			->where(function ($query) {
				$query->where(array('b.endtime' => 0))
					->whereor(array('b.endtime' => USER_ENDTIME_GROUP_UNLIMIT_TYPE))
					->whereor(array('b.endtime >=' => TIMESTAMP));
			})
			->select(array('a.uniacid', 'a.name'))
			->orderby('b.acid DESC')
			->getall('uniacid');

		$message = array(
			'accounts' => $accounts,
			'sms_info' => $sms_info,
			'sms_count_remained' => $sms_count_remained
		);
		if (!empty($settings)) {
			$message['settings'] = $settings;
		}
		iajax(0, $message);
	}
}

if ('system' == $do) {
	if ($_W['isajax']) {
		if (is_error($sms_info)) {
			iajax(-1, $sms_info['message']);
		}
		$setting_sms_blance = setting_load('system_sms_balance');
		$system_sms['system_sms_balance'] = !empty($setting_sms_blance['system_sms_balance']) ? $setting_sms_blance['system_sms_balance'] : 0;

		$setting_sms_sign = setting_load('site_sms_sign');
		$setting_sms_sign = !empty($setting_sms_sign['site_sms_sign']) ? $setting_sms_sign['site_sms_sign'] : array();
		$system_sms['system_sms_sign'] = !empty($setting_sms_sign['system_sms_sign']) ? $setting_sms_sign['system_sms_sign'] : '';

		$user_expire = setting_load('user_expire');
		$user_expire = !empty($user_expire['user_expire']) ? $user_expire['user_expire'] : array();

		$account_sms_expire = setting_load('account_sms_expire');
		$account_sms_expire = !empty($account_sms_expire['account_sms_expire']) ? $account_sms_expire['account_sms_expire'] : array();

		$api_sms_expire = setting_load('api_sms_expire');
		$api_sms_expire = !empty($api_sms_expire['api_sms_expire']) ? $api_sms_expire['api_sms_expire'] : array();

		if (!empty($sms_info['sms_sign'])) {
			foreach ($sms_info['sms_sign'] as $item) {
				$cloud_sms_signs[$item] = $item;
			}
		}

		$user_expire['day'] = !empty($user_expire['day']) ? $user_expire['day'] : 1;
		$user_expire['status'] = !empty($user_expire['status']) ? $user_expire['status'] : 0;

		$account_sms_expire['day'] = !empty($account_sms_expire['day']) ? $account_sms_expire['day'] : 1;
		$account_sms_expire['status'] = !empty($account_sms_expire['status']) ? $account_sms_expire['status'] : 0;

		$api_sms_expire['num'] = !empty($api_sms_expire['num']) ? $api_sms_expire['num'] : 3000;
		$api_sms_expire['status'] = !empty($api_sms_expire['status']) ? $api_sms_expire['status'] : 0;

		if (is_error($sms_count_remained)) {
			iajax(-1, $sms_count_remained['message']);
		}
		$message = array(
			'sms_info' => $sms_info,
			'system_sms' => $system_sms,
			'user_expire' => $user_expire,
			'account_sms_expire' => $account_sms_expire,
			'api_sms_expire' => $api_sms_expire,
			'sms_count_remained' => $sms_count_remained
		);
		iajax(0, $message);
	}
}

if ('change_setting' == $do) {
	$setting_name = safe_gpc_string($_GPC['setting_name']);
	$type = safe_gpc_string($_GPC['type']);
	if (!in_array($setting_name, array('user_expire', 'account_sms_expire', 'api_sms_expire')) || !in_array($type, array('balance', 'status', 'day', 'num', 'status_store_redirect'))) {
		iajax(-1, '参数错误');
	}
	$setting = setting_load($setting_name);
	$setting = !empty($setting[$setting_name]) ? $setting[$setting_name] : array();
	$setting[$type] = safe_gpc_int($_GPC[$type]);
	$result = setting_save($setting, $setting_name);
	if (is_error($result)) {
		iajax(-1, '设置失败', referer());
	}
	iajax(0, '设置成功', referer());
}

if ('setting_sign' == $do) {
	$setting_sign = safe_gpc_string($_GPC['setting_sign']);
	if (is_error($sms_info)) {
		iajax(-1, $sms_info['message']);
	}
	if (!in_array($setting_sign, $sms_info['sms_sign'])) {
		iajax(-1, '当前签名不可用');
	}
	$setting_sms_sign = setting_load('site_sms_sign');
	$setting_sms_sign = !empty($setting_sms_sign['site_sms_sign']) ? $setting_sms_sign['site_sms_sign'] : array();
	$setting_signs = array('system_sms_sign', 'register', 'find_password', 'user_expire', 'api_sms_expire', 'account_sms_expire');
	foreach ($setting_signs as $sign) {
		$setting_sms_sign[$sign] = $setting_sign;
	}
	$result = setting_save($setting_sms_sign, 'site_sms_sign');
	if (is_error($result)) {
		iajax(-1, '设置失败');
	}
	iajax(0, '设置成功');
}

if ('setting_balance' == $do) {
	$balance = max(0, safe_gpc_int($_GPC['balance']));
	if (is_error($sms_count_remained)) {
		iajax(-1, $sms_count_remained['message']);
	}
	if ($balance > $sms_count_remained) {
		iajax(-1, '您设置的短信数量超过了平台当前可用数量：' . $sms_count_remained);
	}
	$result = setting_save($balance, 'system_sms_balance');
	if (is_error($result)) {
		iajax(-1, '设置失败');
	}
	iajax(0, '设置成功');
}

template('cloud/sms-share');

