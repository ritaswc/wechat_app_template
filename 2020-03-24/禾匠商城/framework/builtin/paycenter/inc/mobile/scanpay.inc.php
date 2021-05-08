<?php

defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
paycenter_check_login();
$user_permission = permission_account_user('system');
$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
if ($_W['account']['level'] != ACCOUNT_SERVICE_VERIFY) {
	message('公众号权限不足', '', 'error');
}
if ('post' == $op) {
	if (checksubmit()) {
		$fee = trim($_GPC['fee']) ? trim($_GPC['fee']) : message('收款金额有误', '', 'error');
		$body = trim($_GPC['body']) ? trim($_GPC['body']) : '收银台收款' . $fee;
		$data = array(
			'uniacid' => $_W['uniacid'],
			'clerk_id' => $_W['user']['clerk_id'],
			'clerk_type' => $_W['user']['clerk_type'],
			'store_id' => $_W['user']['store_id'],
			'body' => $body,
			'fee' => $fee,
			'final_fee' => $fee,
			'credit_status' => 1,
			'createtime' => TIMESTAMP,
		);
		table('paycenter_order')->fill($data)->save();
		$id = pdo_insertid();
		header('location:' . $this->createMobileUrl('scanpay', array('op' => 'qrcode', 'id' => $id)));
		die;
	}
}

if ('qrcode' == $op) {
	$id = intval($_GPC['id']);
	$order = table('paycenter_order')->getById($id, $_W['uniacid']);
	if (empty($order)) {
		message('订单不存在或已删除', '', 'error');
	}
	if (1 == $order['status']) {
		message('该订单已付款', '', 'error');
	}
}

if ('list' == $op) {
	$period = intval($_GPC['period']);
	$where = array(
		'uniacid' => $_W['uniacid'],
		'status' => 1,
		'clerk_id' => $_W['user']['clerk_id'],
	);
	if ($period <= 0) {
		$starttime = strtotime(date('Y-m-d')) + $period * 86400;
		$endtime = $starttime + 86400;
		$where['paytime >='] = $starttime;
		$where['paytime <='] = $endtime;
	}
	$orders = table('paycenter_order')
		->where($where)
		->orderby(array('paytime' => 'DESC'))
		->getall();
}

if ('detail' == $op) {
	$id = intval($_GPC['id']);
	$order = table('paycenter_order')->getById($id, $_W['uniacid']);
	if (empty($order)) {
		message('订单不存在', '', '');
	} else {
		$store_id = $order['store_id'];
		$types = paycenter_order_types();
		$trade_types = paycenter_order_trade_types();
		$status = paycenter_order_status();
		$store_info = table('activity_stores')
			->select('business_name')
			->where(array(
				'id' => $id, 
				'uniacid' => $_W['uniacid']
			))
			->get();
	}
}

include $this->template('scanpay');