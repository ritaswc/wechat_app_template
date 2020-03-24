<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('mc');
load()->model('card');
load()->model('module');

permission_check_account_user('mc_member');
$creditnames = uni_setting_load('creditnames');
$creditnames = $creditnames['creditnames'];
$dos = array('consume', 'user', 'modal', 'credit', 'card', 'cardsn', 'tpl', 'cardconsume');
$do = in_array($do, $dos) ? $do : 'tpl';

if ('user' == $do) {
	$type = trim($_GPC['type']);
	if (!in_array($type, array('uid', 'mobile'))) {
		$type = 'mobile';
	}
	$username = trim($_GPC['username']);
	$data = table('mc_members')
		->where(array(
			'uniacid' => $_W['uniacid'],
			$type => $username
		))
		->getall();
	if (empty($data)) {
		exit(json_encode(array('error' => 'empty', 'message' => '没有找到对应用户')));
	} elseif (count($data) > 1) {
		exit(json_encode(array('error' => 'not-unique', 'message' => '用户不唯一,请重新输入用户信息')));
	} else {
		$card = array();
		$user = $data[0];
		$user['groupname'] = $_W['account']['groups'][$user['groupid']]['title'];
		$we7_coupon_info = module_fetch('we7_coupon');
		if (!empty($we7_coupon_info)) {
			$card = card_setting();
			$member = table('mc_card_members')
				->where(array(
					'uniacid' => $_W['uniacid'],
					'uid' => $user['uid']
				))
				->get();
			if (!empty($card) && 1 == $card['status']) {
				if (!empty($member)) {
					$str = "会员卡号:{$member['cardsn']}.";
					$user['discount'] = $card['discount'][$user['groupid']];
					$user['cardsn'] = $member['cardsn'];
					if (!empty($user['discount']) && !empty($user['discount']['discount'])) {
						$str .= "折扣:满{$user['discount']['condition']}元";
						if (1 == $card['discount_type']) {
							$str .= "减{$user['discount']['discount']}元";
						} else {
							$discount = $user['discount']['discount'] * 10;
							$str .= "打{$discount}折";
						}
						$user['discount_cn'] = $str;
					}
				} else {
					$user['discount_cn'] = '会员未领取会员卡,不能享受优惠';
				}
			} else {
				$user['discount_cn'] = '商家未开启会员卡功能';
			}
		}

		$html = "姓名:{$user['realname']},会员组:{$user['groupname']}<br>";
		if (!empty($we7_coupon_info)) {
			$html .= "{$user['discount_cn']}<br>";
		}
		$html .= "余额:{$user['credit2']}元,积分:{$user['credit1']}<br>";
		if (!empty($we7_coupon_info) && !empty($card) && $card['offset_rate'] > 0 && $card['offset_max'] > 0) {
			$html .= "{$card['offset_rate']}积分可抵消1元。最多可抵消{$card['offset_max']}元";
		}

		exit(json_encode(array('error' => 'none', 'user' => $user, 'html' => $html, 'card' => $card, 'group' => $_W['account']['groups'], 'grouplevel' => $_W['account']['grouplevel'])));
	}
}

if ('cardsn' == $do) {
	$uid = intval($_GPC['uid']);
	$cardsn = trim($_GPC['cardsn']);
	$type = trim($_GPC['type']);
	if ($_W['isajax'] && 'check' == $type) {
		$data = table('mc_card_members')
			->where(array(
				'cardsn' => $cardsn,
				'uniacid' => $_W['uniacid']
			))
			->get();
		if (!empty($data)) {
			exit(json_encode(array('valid' => false)));
		} else {
			exit(json_encode(array('valid' => true)));
		}
	} else {
		table('mc_card_members')
			->where(array(
				'uid' => $uid,
				'uniacid' => $_W['uniacid']
			))
			->fill(array('cardsn' => $cardsn))
			->save();
		exit('success');
	}
}

if ($_W['isajax'] && !in_array($do, array('user', 'clerk', 'cardsn', 'cardconsume'))) {
	$uid = intval($_GPC['uid']);
	$user = table('mc_members')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid
		))
		->get();
	if (empty($user)) {
		exit('会员不存在');
	}
}

if ('consume' == $do) {
	$total = $money = floatval($_GPC['total']);
	if (!$total) {
		exit('消费金额不能为空');
	}
	$log = "系统日志:会员消费【{$total}】元";
	load()->model('card');
	$user['groupname'] = $_W['account']['groups'][$user['groupid']]['title'];

	$card = array();
	$card = card_setting();
	$member = table('mc_card_members')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'uid' => $user['uid']
		))
		->get();
	if (!empty($card) && 1 == $card['status'] && !empty($member)) {
		$user['discount'] = $card['discount'][$user['groupid']];
		if (!empty($user['discount']) && !empty($user['discount']['discount'])) {
			if ($total >= $user['discount']['condition']) {
				$log .= ",所在会员组【{$user['groupname']}】,可享受满【{$user['discount']['condition']}】元";
				if (1 == $card['discount_type']) {
					$log .= "减【{$user['discount']['discount']}】元";
					$money = $total - $user['discount']['discount'];
				} else {
					$discount = $user['discount']['discount'] * 10;
					$log .= "打【{$discount}】折";
					$money = $total * $user['discount']['discount'];
					$money = sprintf('%.1f', $money);
				}
				if ($money < 0) {
					$money = 0;
				}
				$log .= ",实收金额【{$money}】元";
			}
		}
	}
	$post_money = floatval($_GPC['money']);
	if ($post_money != $money) {
		exit('实收金额错误');
	}
	$post_credit1 = intval($_GPC['credit1']);
	if ($post_credit1 > 0) {
		if ($post_credit1 > $user['credit1']) {
			exit('超过会员账户可用积分');
		}
	}
	$post_offset_money = intval($_GPC['offset_money']);
	$offset_money = 0;
	if ($post_credit1 && $card['offset_rate'] > 0 && $card['offset_max'] > 0) {
		$offset_money = min($card['offset_max'], $post_credit1 / $card['offset_rate']);
		if ($offset_money != $post_offset_money) {
			exit('积分抵消金额错误');
		}
		$credit1 = $post_credit1;
		$log .= ",使用【{$post_credit1}】积分抵消【{$offset_money}】元";
	}

	$credit2 = floatval($_GPC['credit2']);
	if ($credit2 > 0) {
		if ($credit2 > $user['credit2']) {
			exit('超过会员账户可用余额');
		}
		$log .= ",使用余额支付【{$credit2}】元";
	}

	$cash = floatval($_GPC['cash']);
	$sum = $credit2 + $cash + $offset_money;
	$final_cash = $money - $credit2 - $offset_money;
	$return_cash = $sum - $money;
	if ($sum < $money) {
		exit('支付金额小于实收金额');
	}
	if ($cash > 0) {
		$log .= ",使用现金支付【{$cash}】元";
	}
	if ($return_cash > 0) {
		$log .= ",找零【{$return_cash}】元";
	}
	if (!empty($_GPC['remark'])) {
		$note = "店员备注：{$_GPC['remark']}";
	}
	$log = $note . $log;
	if ($credit2 > 0) {
		$status = mc_credit_update($uid, 'credit2', -$credit2, array(0, $log, 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
		if (is_error($status)) {
			exit($status['message']);
		}
	}
	if ($credit1 > 0) {
		$status = mc_credit_update($uid, 'credit1', -$credit1, array(0, $log, 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
		if (is_error($status)) {
			exit($status['message']);
		}
	}

	$data = array(
		'uniacid' => $_W['uniacid'],
		'uid' => $uid,
		'fee' => $total,
		'final_fee' => $money,
		'credit1' => $post_credit1,
		'credit1_fee' => $offset_money,
		'credit2' => $credit2,
		'cash' => $cash,
		'final_cash' => $final_cash,
		'return_cash' => $return_cash,
		'remark' => $log,
		'clerk_id' => $_W['user']['clerk_id'],
		'store_id' => $_W['user']['store_id'],
		'clerk_type' => $_W['user']['clerk_type'],
		'createtime' => TIMESTAMP,
	);
	table('mc_cash_record')->fill($data)->save();

	$tips = "用户消费{$money}元,使用{$data['credit1']}积分,抵现{$data['credit1_fee']}元,使用余额支付{$data['credit2']}元,现金支付{$data['final_cash']}元";
	$recharges_set = card_params_setting('cardRecharge');
	$grant_rate_switch = intval($recharges_set['params']['grant_rate_switch']);
	$grant_credit1_enable = false;
	$grant_money = $money;
	if (!empty($card) && $card['grant_rate'] > 0 && !empty($member)) {
		if (empty($recharges_set['params']['recharge_type'])) {
			$grant_credit1_enable = true;
		} else {
			if ('1' == $grant_rate_switch) {
				$grant_money = $data['cash'] + $data['credit2'];
				$grant_credit1_enable = true;
			} else {
				if (!empty($data['cash'])) {
					$grant_money = $data['cash'];
					$grant_credit1_enable = true;
				}
			}
		}
	}
	if (!empty($grant_credit1_enable)) {
		$num = floor($grant_money * $card['grant_rate']);
		$tips .= "，积分赠送比率为:【1：{$card['grant_rate']}】,共赠送【{$num}】积分";
		mc_credit_update($uid, 'credit1', $num, array(0, $tips, 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
	}
	$openid = table('mc_mapping_fans')->where(array('uniacid' => $_W['uniacid'], 'uid' => $uid))->getcolumn('openid');
	$consume_tips = array(
		'uid' => $uid,
		'credit2_num' => $money,
		'credit1_num' => $num,
		'store' => '系统后台',
		'remark' => $tips,
	);
	if (!empty($openid)) {
		mc_notice_consume($openid, '会员消费通知', $consume_tips);
	}
	exit('success');
}

if ('credit' == $do) {
	$uid = intval($_GPC['uid']);
	$type = trim($_GPC['type']);
	$num = floatval($_GPC['num']);
	$names = array('credit1' => $creditnames['credit1']['title'], 'credit2' => $creditnames['credit2']['title']);
	$credits = mc_credit_fetch($uid);
	if ($num < 0 && abs($num) > $credits[$type]) {
		exit("会员账户{$names[$type]}不够");
	}
	$status = mc_credit_update($uid, $type, $num, array($_W['user']['uid'], trim($_GPC['remark']), 'system', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
	if (is_error($status)) {
		exit($status['message']);
	}
	if ('credit1' == $type) {
		mc_group_update($uid);
	}
	$openid = table('mc_mapping_fans')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'uid' => $uid
		))
		->getcolumn('openid');
	if (!empty($openid)) {
		if ('credit1' == $type) {
			mc_notice_credit1($openid, $uid, $num, '管理员后台操作' . $creditnames['credit1']['title']);
		}
		if ('credit2' == $type) {
			if ($num > 0) {
				mc_notice_recharge($openid, $uid, $num, '', "管理员后台操作{$creditnames['credit1']['title']},增加{$value}{$creditnames['credit2']['title']}");
			} else {
				mc_notice_credit2($openid, $uid, $num, 0, '', '', "管理员后台操作{$creditnames['credit1']['title']},减少{$value}{$creditnames['credit2']['title']}");
			}
		}
	}
	exit('success');
}

if ('card' == $do) {
	load()->model('card');
	$card = card_setting();
	if (empty($card)) {
		exit('公众号未设置会员卡');
	}
	$member = table('mc_card_members')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'uid' => $user['uid']
		))
		->get();
	if (!empty($member)) {
		exit('该会员已领取会员卡');
	}
	$cardsn = $card['format'];
	preg_match_all('/(\*+)/', $card['format'], $matchs);
	if (!empty($matchs)) {
		foreach ($matchs[1] as $row) {
			$cardsn = str_replace($row, random(strlen($row), 1), $cardsn);
		}
	}
	preg_match('/(\#+)/', $card['format'], $matchs);
	$length = strlen($matchs[1]);
	$pos = strpos($card['format'], '#');
	$cardsn = str_replace($matchs[1], str_pad($card['snpos']++, $length - strlen($number), '0', STR_PAD_LEFT), $cardsn);

	$record = array(
		'uniacid' => $_W['uniacid'],
		'openid' => '',
		'uid' => $uid,
		'cid' => $card['id'],
		'cardsn' => $_GPC['username'],
		'status' => '1',
		'createtime' => TIMESTAMP,
		'endtime' => TIMESTAMP,
	);

	if (table('mc_card_members')->fill($record)->save()) {
		table('mc_card')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'id' => $card['id']
			))
			->fill(array('snpos' => $card['snpos']))
			->save();
		$notice = '';
		if ($card['grant']['credit1'] > 0) {
			$log = array(
				$uid,
				"领取会员卡，赠送{$card['grant']['credit1']}积分",
				'system',
				$_W['user']['clerk_id'],
				$_W['user']['store_id'],
				$_W['user']['clerk_type'],
			);
			mc_credit_update($uid, 'credit1', $card['grant']['credit1'], $log);
		}
		if ($card['grant']['credit2'] > 0) {
			$log = array(
				$uid,
				"领取会员卡，赠送{$card['credit2']['credit1']}余额",
				'system',
				$_W['user']['clerk_id'],
				$_W['user']['store_id'],
				$_W['user']['clerk_type'],
			);
			mc_credit_update($uid, 'credit2', $card['grant']['credit2'], $log);
		}
		if (!empty($card['grant']['coupon']) && is_array($card['grant']['coupon'])) {
			foreach ($card['grant']['coupon'] as $grant_coupon) {
				load()->model('activity');
				activity_coupon_grant($grant_coupon['coupon'], $uid);
			}
		}
		exit('success');
	}
}

if ('cardconsume' == $do) {
	load()->model('activity');
	$code = trim($_GPC['code']);
	$coupon_record = table('coupon_record')
		->where(array(
			'code' => $code,
			'status' => '1'
		))
		->get();
	if (!empty($coupon_record)) {
		$status = activity_coupon_use($coupon_record['couponid'], $coupon_record['id'], 'paycenter');
		if (is_error($status)) {
			exit($status['message']);
		} else {
			exit('success');
		}
	} else {
		exit('卡券已核销或失效');
	}
}

if ('group' == $do) {
	$credit6 = floatval($_GPC['credit6']);
	$credit = $credit1 + $credit6;
	if ($credit < 0) {
		exit('积分和贡献相加不能小于0');
	}
	if ($credit6 != $user['credit6']) {
		mc_credit_update($uid, 'credit6', (-$user['credit6'] + $credit6), array(0, '通过修改贡献值,来变更会员用户组', 'group', $_W['user']['clerk_id'], $_W['user']['store_id'], $_W['user']['clerk_type']));
	}
	$groupid = $user['groupid'];
	$_W['member'] = $user;
	$_W['openid'] = table('mc_mapping_fans')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'uid' => $user['uid']
		))
		->getcolumn('openid');
	mc_group_update();
	exit('success');
}
template('mc/trade');