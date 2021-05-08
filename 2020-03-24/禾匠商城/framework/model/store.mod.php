<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

function store_goods_type_info($group = '') {
	$data = array(
		STORE_TYPE_MODULE => array('title' => '公众号应用', 'type' => STORE_TYPE_MODULE, 'sign' => ACCOUNT_TYPE_SIGN, 'group' => 'module'),
		STORE_TYPE_WXAPP_MODULE => array('title' => '微信小程序应用', 'type' => STORE_TYPE_WXAPP_MODULE, 'sign' => WXAPP_TYPE_SIGN, 'group' => 'module'),
		STORE_TYPE_WEBAPP_MODULE => array('title' => 'PC应用', 'type' => STORE_TYPE_WEBAPP_MODULE, 'sign' => WEBAPP_TYPE_SIGN, 'group' => 'module'),
		STORE_TYPE_PHONEAPP_MODULE => array('title' => 'APP应用', 'type' => STORE_TYPE_PHONEAPP_MODULE, 'sign' => PHONEAPP_TYPE_SIGN, 'group' => 'module'),
		STORE_TYPE_XZAPP_MODULE => array('title' => '熊掌号应用', 'type' => STORE_TYPE_XZAPP_MODULE, 'sign' => XZAPP_TYPE_SIGN, 'group' => 'module'),
		STORE_TYPE_ALIAPP_MODULE => array('title' => '支付宝小程序应用', 'type' => STORE_TYPE_ALIAPP_MODULE, 'sign' => ALIAPP_TYPE_SIGN, 'group' => 'module'),
		STORE_TYPE_BAIDUAPP_MODULE => array('title' => '百度小程序应用', 'type' => STORE_TYPE_BAIDUAPP_MODULE, 'sign' => BAIDUAPP_TYPE_SIGN, 'group' => 'module'),
		STORE_TYPE_TOUTIAOAPP_MODULE => array('title' => '头条小程序应用', 'type' => STORE_TYPE_TOUTIAOAPP_MODULE, 'sign' => TOUTIAOAPP_TYPE_SIGN, 'group' => 'module'),
		STORE_TYPE_API => array('title' => '应用访问流量API', 'type' => STORE_TYPE_API, 'group' => ''),
		STORE_TYPE_PACKAGE => array('title' => '应用权限组', 'type' => STORE_TYPE_PACKAGE, 'group' => ''),
		STORE_TYPE_USER_PACKAGE => array('title' => '用户权限组', 'type' => STORE_TYPE_USER_PACKAGE, 'group' => ''),
		STORE_TYPE_ACCOUNT_PACKAGE => array('title' => '账号权限组', 'type' => STORE_TYPE_ACCOUNT_PACKAGE, 'group' => ''),

		STORE_TYPE_ACCOUNT => array('title' => '公众号平台', 'type' => STORE_TYPE_ACCOUNT, 'group' => 'account_num'),
		STORE_TYPE_WXAPP => array('title' => '微信小程序平台', 'type' => STORE_TYPE_WXAPP, 'group' => 'account_num'),
		STORE_TYPE_WEBAPP => array('title' => 'PC平台', 'type' => STORE_TYPE_WEBAPP, 'group' => 'account_num'),
		STORE_TYPE_PHONEAPP => array('title' => 'APP平台', 'type' => STORE_TYPE_PHONEAPP, 'group' => 'account_num'),
		STORE_TYPE_XZAPP => array('title' => '熊掌号平台', 'type' => STORE_TYPE_XZAPP, 'group' => 'account_num'),
		STORE_TYPE_ALIAPP => array('title' => '支付宝小程序平台', 'type' => STORE_TYPE_ALIAPP, 'group' => 'account_num'),
		STORE_TYPE_BAIDUAPP => array('title' => '百度小程序平台', 'type' => STORE_TYPE_BAIDUAPP, 'group' => 'account_num'),
		STORE_TYPE_TOUTIAOAPP => array('title' => '头条小程序平台', 'type' => STORE_TYPE_TOUTIAOAPP, 'group' => 'account_num'),

		STORE_TYPE_ACCOUNT_RENEW => array('title' => '公众号', 'type' => STORE_TYPE_ACCOUNT_RENEW, 'num_sign' => 'account_num', 'group' => 'renew'),
		STORE_TYPE_WXAPP_RENEW => array('title' => '微信小程序', 'type' => STORE_TYPE_WXAPP_RENEW, 'num_sign' => 'wxapp_num', 'group' => 'renew'),
		STORE_TYPE_WEBAPP_RENEW => array('title' => 'PC', 'type' => STORE_TYPE_WEBAPP_RENEW, 'num_sign' => 'webapp_num', 'group' => 'renew'),
		STORE_TYPE_PHONEAPP_RENEW => array('title' => 'APP', 'type' => STORE_TYPE_PHONEAPP_RENEW, 'num_sign' => 'phoneapp_num', 'group' => 'renew'),
		STORE_TYPE_XZAPP_RENEW => array('title' => '熊掌号', 'type' => STORE_TYPE_XZAPP_RENEW, 'num_sign' => 'xzapp_num', 'group' => 'renew'),
		STORE_TYPE_ALIAPP_RENEW => array('title' => '支付宝小程序', 'type' => STORE_TYPE_ALIAPP_RENEW, 'num_sign' => 'aliapp_num', 'group' => 'renew'),
		STORE_TYPE_BAIDUAPP_RENEW => array('title' => '百度小程序', 'type' => STORE_TYPE_BAIDUAPP_RENEW, 'num_sign' => 'baiduapp_num', 'group' => 'renew'),
		STORE_TYPE_TOUTIAOAPP_RENEW => array('title' => '头条小程序', 'type' => STORE_TYPE_TOUTIAOAPP_RENEW, 'num_sign' => 'toutiaoapp_num', 'group' => 'renew'),
	);
	if (!empty($group)) {
		foreach ($data as $k => $item) {
			if ($item['group'] != $group) {
				unset($data[$k]);
			}
		}
	} else {
		foreach ($data as $k => $item) {
			if (!empty($item['group'])) {
				$data[$item['group']][$item['type']] = $item;
			}
		}
	}
	return $data;

}


function store_goods_info($id) {
	$result = table('site_store_goods')->getById(intval($id));
	return $result ? $result : array();
}

function store_goods_changestatus($id) {
	$result = false;
	$id = intval($id);
	if (empty($id)) {
		return $result;
	}
	$if_exist = pdo_get('site_store_goods', array('id' => $id));
	if (!empty($if_exist)) {
		$status = $if_exist['status'] == 1 ? 0 : 1;
		$data = array('status' => $status);
		$result = pdo_update('site_store_goods', $data, array('id' => $id));
	}
	return $result;
}

function store_goods_delete($id) {
	$result = false;
	$id = intval($id);
	if (empty($id)) {
		return $result;
	}
	$result = pdo_update('site_store_goods', array('status' => 2), array('id' => $id));
	return $result;
}

function update_wish_goods_info($update_data, $module_name) {
	if (!empty($update_data['title'])) {
		$data['title'] = $update_data['title'];
		$data['title_initial'] = $update_data['title_initial'];
	}

	if (!empty($update_data['logo'])) {
		$data['logo'] = $update_data['logo'];
	}

	$store_goods_exists = pdo_get('site_store_goods', array('module' => $module_name));
	$store_goods_cloud_exists = pdo_get('site_store_goods_cloud', array('name' => $module_name));
	$module_exists = pdo_get('modules', array('name' => $module_name));

	if ($store_goods_exists) {
		pdo_update('site_store_goods', $data, array('module' => $module_name));
	}

	if ($module_exists) {
		pdo_update('modules', $data, array('name' => $module_name));
	}

	if ($store_goods_cloud_exists) {
		unset($data['title_initial']);
		pdo_update('site_store_goods_cloud', $data, array('name' => $module_name));
	}
	cache_build_module_info($module_name);
	return true;
}


function store_goods_post($data) {
	$result = false;
	if (empty($data)) {
		return $result;
	}
	$post = array();
	if (!empty($data['title'])) {
		$post['title'] = trim($data['title']);
	}
	if (is_numeric($data['price'])) {
		$post['price'] = $data['price'];
	}
	$post['slide'] = $data['slide'];
	if (!empty($data['status'])) {
		$post['status'] = 1;
	}
	if (!empty($data['ability']) || !empty($data['synopsis'])) {
		$post['synopsis'] = empty($data['ability']) ? trim($data['synopsis']) : trim($data['ability']);
	}
	if (!empty($data['description'])) {
		$post['description'] = trim($data['description']);
	}
	if (!empty($data['api_num'])) {
		$post['api_num'] = intval($data['api_num']);
	}
	if (!empty($data['unit'])) {
		$post['unit'] = $data['unit'];
	} else {
		if ($data['type'] != STORE_TYPE_API) {
			$post['unit'] = 'month';
		}
	}
	$post['account_num'] = $data['account_num'];
	$post['wxapp_num'] = $data['wxapp_num'];
	$post['webapp_num'] = $data['webapp_num'];
	$post['phoneapp_num'] = $data['phoneapp_num'];
	$post['xzapp_num'] = $data['xzapp_num'];
	$post['aliapp_num'] = $data['aliapp_num'];
	$post['baiduapp_num'] = $data['baiduapp_num'];
	$post['toutiaoapp_num'] = $data['toutiaoapp_num'];

	$post['platform_num'] = $data['platform_num'] == 0 ? 1 : $data['platform_num'];
	$post['module_group'] = $data['module_group'];
	$post['user_group'] = $data['user_group'];
	$post['account_group'] = $data['account_group'];
	$post['user_group_price'] = $data['user_group_price'];

	$renews = store_goods_type_info('renew');
	if (in_array($data['type'], array_keys($renews))) {
		$post[$renews[$data['type']]['num_sign']] = $post[$renews[$data['type']]['num_sign']] == 0 ? 1 : $post[$renews[$data['type']]['num_sign']];
	}
	if (!empty($data['id'])) {
		$result = pdo_update('site_store_goods', $post, array('id' => $data['id']));
		if (!empty($data['module'])) {
			$update_data = array('title' => $post['title'], 'title_initial' => get_first_pinyin($post['title']), 'logo' => $data['logo']);
			$result = update_wish_goods_info($update_data, $data['module']);
		}
	} else {
		$post['type'] = $data['type'];
		$post['createtime'] = TIMESTAMP;
		$post['title_initial'] = get_first_pinyin($data['title']);
		if (empty($post['unit'])) {
			$post['unit'] = 'month';
		}
		if ($data['type'] == STORE_TYPE_API) {
			$post['unit'] = 'ten_thousand';
		}
		$post['module'] = trim($data['module']);
		$result = pdo_insert('site_store_goods', $post);
	}
	return $result;
}

function store_order_info($id) {
	$result = table('site_store_order')->getById(intval($id));
	return $result ? $result : array();
}

function store_order_change_price($id, $price) {
	global $_W;
	$result = false;
	$id = intval($id);
	$price = floatval($price);
	$if_exist = store_order_info($id);
	if (empty($id) || empty($if_exist)) {
		return $result;
	}
	if (user_is_vice_founder() || empty($_W['isfounder'])) {
		return $result;
	}
	pdo_update('core_paylog', array('card_fee' => $price), array('module' => 'store', 'tid' => $id));
	$result = pdo_update('site_store_order', array('amount' => $price, 'changeprice' => 1), array('id' => $id));
	return $result;
}


function store_order_delete($id) {
	$result = false;
	$id = intval($id);
	if (empty($id)) {
		return $result;
	}
	$result = pdo_update('site_store_order', array('type' => STORE_ORDER_DELETE), array('id' => $id));
	return $result;
}


function store_add_cash_order($orderid) {
	global $_W;
	$store_setting = $_W['setting']['store'];
	if (empty($store_setting['cash_status']) || empty($store_setting['cash_ratio'])) {
		return error(1, '未开启分销, 或者提成比例为0');
	}
	$order = store_order_info($orderid);
	if (empty($order)) {
		return error(1, '订单不存在');
	}
	if ($order['type'] != STORE_ORDER_FINISH) {
		return error(1, '订单未支付');
	}
	if ($order['amount'] <= 0) {
		return error(1, '订单金额为0');
	}
	$order_cash = pdo_get('site_store_cash_order', array('order_id' => $order['id']));
	if (!empty($order_cash)) {
		return error(1, '分销订单已存在');
	}
	$user_founder = table('users_founder_own_users')->getFounderByUid($order['buyerid']);
	if (empty($user_founder['founder_uid'])) {
		return error(1, '上级用户非副创始人');
	}

	pdo_insert('site_store_cash_order', array(
		'number' => date('YmdHis') . random(6, 1),
		'founder_uid' => $user_founder['founder_uid'],
		'order_id' => $order['id'],
		'goods_id' => $order['goodsid'],
		'order_amount' => $order['amount'],
		'create_time' => TIMESTAMP,
		'status' => 1,
	));
	if (pdo_insertid()) {
		return true;
	} else {
		return error(1, '写入数据失败');
	}
}

function store_get_cash_orders($condition = array(), $page = 1, $psize = 15) {
	global $_W;
	$cash_orders = pdo_getall('site_store_cash_order', $condition, array(), '', 'id DESC', ($page - 1) * $psize . ',' . $psize);
	if (empty($cash_orders)) {
		return array('list' => array(), 'total' => 0);
	}
	$total = pdo_getcolumn('site_store_cash_order', $condition, 'count(*)');
	$goods_ids = $order_ids = array();
	if (empty($_W['setting']['store']['cash_status']) || empty($_W['setting']['store']['cash_ratio'])) {
		$cash_ratio = 0;
	} else {
		$cash_ratio = $_W['setting']['store']['cash_ratio'];
	}
	foreach ($cash_orders as $k => $order) {
		$goods_ids[] = $order['goods_id'];
		$order_ids[] = $order['order_id'];
		$cash_orders[$k]['cash_amount'] = sprintf('%.2f', $order['order_amount'] * $cash_ratio / 100);
	}
	$goods = table('site_store_goods')->where('id', $goods_ids)->getall('id');
	$orders = table('site_store_order')->where('id', $order_ids)->getall('id');
	foreach ($cash_orders as $k => $order) {
		$cash_orders[$k]['order'] = empty($orders[$order['order_id']]) ? array() : $orders[$order['order_id']];
		if (empty($goods[$order['goods_id']])) {
			$cash_orders[$k]['goods'] = array();
		} else {
			if (in_array($goods[$order['goods_id']]['type'], array(STORE_TYPE_MODULE, STORE_TYPE_WXAPP_MODULE))) {
				$cash_orders[$k]['goods'] = module_fetch($goods[$order['goods_id']]['module']);
				$cash_orders[$k]['goods']['type'] = $goods[$order['goods_id']]['type'];
			} else {
				$cash_orders[$k]['goods'] = $goods[$order['goods_id']];
			}
		}
	}
	return array(
		'list' => $cash_orders,
		'total' => $total
	);
}

function store_get_founder_can_cash_amount($founder_id, $has_refuse = false) {
	global $_W;
	$store_setting = $_W['setting']['store'];
	if (empty($store_setting['cash_status']) || empty($store_setting['cash_ratio'])) {
		return 0;
	}
	$status = empty($has_refuse) ? 1 : array(1, 3);
	$can_cash_amount = pdo_getcolumn('site_store_cash_order', array('founder_uid' => $founder_id, 'status' => $status), 'sum(order_amount)');
	return sprintf('%.2f', floatval($can_cash_amount) * $store_setting['cash_ratio'] / 100);
}

function store_add_cash_log($founder_id) {
	$can_cash_amount = store_get_founder_can_cash_amount($founder_id);
	if ($can_cash_amount <= 0) {
		return error(1, '暂无待提现订单');
	}

	pdo_insert('site_store_cash_log', array(
		'number' => date('YmdHis') . random(6, 1),
		'founder_uid' => $founder_id,
		'amount' => $can_cash_amount,
		'create_time' => TIMESTAMP,
		'status' => 1,
	));
	$cash_log_id = pdo_insertid();
	if (empty($cash_log_id)) {
		return error(1, '操作失败, 请重试');
	}
	pdo_update(
		'site_store_cash_order',
		array('status' => 2, 'cash_log_id' => $cash_log_id),
		array('founder_uid' => $founder_id, 'status' => 1)
	);
	return true;
}

function store_get_cash_logs($condition = array(), $page = 1, $psize = 15) {
	$cash_logs = pdo_getall('site_store_cash_log', $condition, array(), '', 'id DESC', ($page - 1) * $psize . ',' . $psize);
	if (empty($cash_logs)) {
		return array('list' => array(), 'total' => 0);
	}
	$founder_uids = array();
	foreach ($cash_logs as $log) {
		$founder_uids[] = $log['founder_uid'];
	}
	$users_info = table('users')->where('uid', $founder_uids)->getall('uid');
	foreach ($cash_logs as $k => $log) {
		$cash_logs[$k]['username'] = empty($users_info[$log['founder_uid']]['username']) ? '' : $users_info[$log['founder_uid']]['username'];
	}
	$total = pdo_getcolumn('site_store_cash_log', $condition, 'count(*)');
	return array(
		'list' => $cash_logs,
		'total' => $total
	);
}