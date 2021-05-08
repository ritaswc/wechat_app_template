<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class RechargeModuleSite extends WeModuleSite {
	public function doMobilePay() {
		global $_W, $_GPC;
		checkauth();
		$type = trim($_GPC['type']) ? trim($_GPC['type']) : 'credit';
		if ('credit' == $type) {
			load()->model('card');
			$recharge_settings = card_params_setting('cardRecharge');
			if (checksubmit()) {
				$fee = floatval($_GPC['fee']);
				$backtype = trim($_GPC['backtype']);
				$back = floatval($_GPC['back']);
				if (empty($fee) || $fee <= 0) {
					message('请选择充值金额', referer(), 'error');
				}
				$chargerecord = array(
					'uid' => $_W['member']['uid'],
					'openid' => $_W['openid'],
					'uniacid' => $_W['uniacid'],
					'tid' => date('YmdHi') . random(8, 1),
					'fee' => $fee,
					'type' => 'credit',
					'tag' => $back,
					'backtype' => $backtype,
					'status' => 0,
					'createtime' => TIMESTAMP,
				);
				if (!table('mc_credits_recharge')->fill($chargerecord)->save()) {
					message('创建充值订单失败，请重试！', url('entry', array('m' => 'recharge', 'do' => 'pay')), 'error');
				}
				$params = array(
					'tid' => $chargerecord['tid'],
					'ordersn' => $chargerecord['tid'],
					'title' => '会员余额充值',
					'fee' => $chargerecord['fee'],
					'user' => $_W['member']['uid'],
				);
				$mine = array();
				if (empty($backtype)) {
					$condition = $fee;
					$mine = array(
						'name' => "充{$condition}送{$back}元",
						'value' => $fee,
					);
				} elseif ('1' == $backtype) {
					$condition = $fee;
					$mine = array(
						'name' => "充{$condition}送{$back}积分",
						'value' => $fee,
					);
				} elseif ('2' == $backtype) {
					$condition = $fee;
				}
				$this->pay($params, $mine);
				exit();
			}
			$member = mc_fetch($_W['member']['uid']);
			$name = $member['mobile'];
			if (empty($name)) {
				$name = $member['realname'];
			}
			if (empty($name)) {
				$name = $member['uid'];
			}
			include $this->template('recharge');
		} else {
			$fee = floatval($_GPC['fee']);
			if (!$fee) {
				message('充值金额不能为0', referer(), 'error');
			}
			if ($fee <= 0) {
				message('请输入充值的金额', referer(), 'error');
			}
			$setting = table('mc_card')->getByStatus(1, $_W['uniacid']);
			if (empty($setting)) {
				message('会员卡未开启,请联系商家', referer(), 'error');
			}
			if ('card_nums' == $type) {
				if (!$setting['nums_status']) {
					message("会员卡未开启{$setting['nums_text']}充值,请联系商家", referer(), 'error');
				}
				$setting['nums'] = iunserializer($setting['nums']);
				$num_keys = array_keys($setting['nums']);
				if (!in_array($fee, $num_keys)) {
					message('充值金额错误,请联系商家', referer(), 'error');
				}
				foreach ($setting['nums'] as $key => $val) {
					if ($fee == $val['recharge']) {
						$num_back = $val['num'];
					}
				}
				$mine = array(
					'name' => "充{$fee}送{$num_back}次",
					'value' => "送{$num_back}次",
				);
				$tag = $num_back;
			}
			if ('card_times' == $type) {
				if (!$setting['times_status']) {
					message("会员卡未开启{$setting['times_text']}充值,请联系商家", referer(), 'error');
				}

				$setting['times'] = iunserializer($setting['times']);
				$time_keys = array_keys($setting['times']);
				if (!in_array($fee, $time_keys)) {
					message('充值金额错误,请联系商家', referer(), 'error');
				}
				foreach ($setting['times'] as $key => $val) {
					if ($fee == $val['recharge']) {
						$time_back = $val['time'];
					}
				}
				$member_card = table('mc_card_members')->getByUid($_W['member']['uid'], $_W['uniacid']);
				if ($member_card['endtime'] > TIMESTAMP) {
					$endtime = $member_card['endtime'] + time_back * 86400;
				} else {
					$endtime = strtotime($time_back . 'days');
				}
				$mine = array(
					'name' => "充{$fee}送{$time_back}天",
					'value' => date('Y-m-d', $endtime) . '到期',
				);
				$tag = $time_back;
			}
			$chargerecord = table('mc_credits_recharge')
				->where(array(
					'uniacid' => $_W['uniacid'],
					'uid' => $_W['member']['uid'],
					'fee' => $fee,
					'type' => $type,
					'status' => 0,
					'tag' => $tag,
				))
				->get();

			if (empty($chargerecord)) {
				$chargerecord = array(
					'uid' => $_W['member']['uid'],
					'openid' => $_W['openid'],
					'uniacid' => $_W['uniacid'],
					'tid' => date('YmdHi') . random(8, 1),
					'fee' => $fee,
					'type' => $type,
					'tag' => $tag,
					'status' => 0,
					'createtime' => TIMESTAMP,
				);
				if (!table('mc_credits_recharge')->fill($chargerecord)->save()) {
					message('创建充值订单失败，请重试！', url('mc/card/mycard'), 'error');
				}
			}
			$types = array(
				'card_nums' => $setting['nums_text'],
				'card_times' => $setting['times_text'],
			);
			$params = array(
				'tid' => $chargerecord['tid'],
				'ordersn' => $chargerecord['tid'],
				'title' => "会员卡{$types[$type]}充值",
				'fee' => $chargerecord['fee'],
				'user' => $_W['member']['uid'],
			);
			$this->pay($params, $mine);
			exit();
		}
	}

	
	public function payResult($params) {
		global $_W;
		load()->model('mc');
		load()->model('card');
		$order = table('mc_credits_recharge')->where(array('tid' => $params['tid']))->get();
		if ('success' == $params['result'] && 'notify' == $params['from']) {
			$fee = $params['fee'];
			$total_fee = $fee;
			$data = array('status' => 'success' == $params['result'] ? 1 : -1);
						if ('wechat' == $params['type']) {
				$data['transid'] = $params['tag']['transaction_id'];
				$params['user'] = mc_openid2uid($params['user']);
			}
			table('mc_credits_recharge')->where(array('tid' => $params['tid']))->fill($data)->save();
			$paydata = array('wechat' => '微信', 'alipay' => '支付宝', 'baifubao' => '百付宝', 'unionpay' => '银联');
						if (empty($order['type']) || 'credit' == $order['type']) {
				$setting = uni_setting($_W['uniacid'], array('creditbehaviors', 'recharge'));
				$credit = $setting['creditbehaviors']['currency'];
				$recharge_settings = card_params_setting('cardRecharge');
				$recharge_params = $recharge_settings['params'];
				if (empty($credit)) {
					message('站点积分行为参数配置错误,请联系服务商', '', 'error');
				} else {
					if ('1' == $recharge_params['recharge_type']) {
						$recharges = $recharge_params['recharges'];
					}
					if ('2' == $order['backtype']) {
						$total_fee = $fee;
					} else {
						foreach ($recharges as $key => $recharge) {
							if ($recharge['backtype'] == $order['backtype'] && $recharge['condition'] == $order['fee']) {
								if ('1' == $order['backtype']) {
									$total_fee = $fee;
									$add_credit = $recharge['back'];
								} else {
									$total_fee = $fee + $recharge['back'];
								}
							}
						}
					}
					if ('1' == $order['backtype']) {
						$add_str = ",充值成功,返积分{$add_credit}分,本次操作共增加余额{$total_fee}元,积分{$add_credit}分";
						$remark = '用户通过' . $paydata[$params['type']] . '充值' . $fee . $add_str;
						$record[] = $params['user'];
						$record[] = $remark;
						mc_credit_update($order['uid'], 'credit1', $add_credit, $record);
						mc_credit_update($order['uid'], 'credit2', $total_fee, $record);
						mc_notice_recharge($order['openid'], $order['uid'], $total_fee, '', $remark);
					} else {
						$add_str = ",充值成功,本次操作共增加余额{$total_fee}元";
						$remark = '用户通过' . $paydata[$params['type']] . '充值' . $fee . $add_str;
						$record[] = $params['user'];
						$record[] = $remark;
						mc_credit_update($order['uid'], 'credit2', $total_fee, $record);
						mc_notice_recharge($order['openid'], $order['uid'], $total_fee, '', $remark);
					}
				}
			}

						if ('card_nums' == $order['type']) {
				$member_card = table('mc_card_members')->getByUid($order['uid'], $_W['uniacid']);
				$total_num = $member_card['nums'] + $order['tag'];
				table('mc_card_members')
					->where(array(
						'uniacid' => $order['uniacid'],
						'uid' => $order['uid']
					))
					->fill( array('nums' => $total_num))
					->save();
								$log = array(
					'uniacid' => $order['uniacid'],
					'uid' => $order['uid'],
					'type' => 'nums',
					'fee' => $params['fee'],
					'model' => '1',
					'tag' => $order['tag'], 					'note' => date('Y-m-d H:i') . "通过{$paydata[$params['type']]}充值{$params['fee']}元，返{$order['tag']}次，总共剩余{$total_num}次",
					'addtime' => TIMESTAMP,
				);
				table('mc_card_record')->fill($log)->save();
				$type = table('mc_card')->where(array('uniacid' => $order['uniacid']))->getcolumn('nums_text');
				$total_num = $member_card['nums'] + $order['tag'];
				mc_notice_nums_plus($order['openid'], $type, $order['tag'], $total_num);
			}

						if ('card_times' == $order['type']) {
				$member_card = table('mc_card_members')->getByUid($order['uid'], $_W['uniacid']);
				if ($member_card['endtime'] > TIMESTAMP) {
					$endtime = $member_card['endtime'] + $order['tag'] * 86400;
				} else {
					$endtime = strtotime($order['tag'] . 'days');
				}
				table('mc_card_members')
					->where(array(
						'uniacid' => $order['uniacid'],
						'uid' => $order['uid']
					))
					->fill(array('endtime' => $endtime))
					->save();
				$log = array(
					'uniacid' => $order['uniacid'],
					'uid' => $order['uid'],
					'type' => 'times',
					'model' => '1',
					'fee' => $params['fee'],
					'tag' => $order['tag'], 					'note' => date('Y-m-d H:i') . "通过{$paydata[$params['type']]}充值{$params['fee']}元，返{$order['tag']}天，充值后到期时间:" . date('Y-m-d', $endtime),
					'addtime' => TIMESTAMP,
				);
				table('mc_card_record')->fill($log)->save();
				$type = table('mc_card')->where(array('uniacid' => $order['uniacid']))->getcolumn('times_text');
				$endtime = date('Y-m-d', $endtime);
				mc_notice_times_plus($order['openid'], $member_card['cardsn'], $type, $fee, $order['tag'], $endtime);
			}
		}
		if ('credit' == $order['type'] || '' == $order['type']) {
			$url = murl('mc/home');
		} else {
			$url = murl('mc/card/mycard');
		}
				if ('return' == $params['from']) {
			if ('success' == $params['result']) {
				message('支付成功！', $_W['siteroot'] . 'app/' . $url, 'success');
			} else {
				message('支付失败！', $_W['siteroot'] . 'app/' . $url, 'error');
			}
		}
	}

	protected function pay($params = array(), $mine = array()) {
		global $_W;
		$params['module'] = $this->module['name'];
		$log = table('core_paylog')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'module' => $params['module'],
				'tid' => $params['tid'],
			))
			->get();
		if (!empty($log) && '1' == $log['status']) {
			itoast('这个订单已经支付成功, 不需要重复支付.', '', 'info');
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if (!is_array($setting['payment'])) {
			itoast('没有有效的支付方式, 请联系网站管理员.', '', 'error');
		}
		if (empty($log)) {
			$log = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $_W['member']['uid'],
				'module' => $this->module['name'], 				'tid' => $params['tid'],
				'fee' => $params['fee'],
				'card_fee' => $params['fee'],
				'status' => '0',
				'is_usecard' => '0',
			);
			table('core_paylog')->fill($log)->save();
		}
		$pay = $setting['payment'];
		foreach ($pay as &$value) {
			$value['switch'] = $value['recharge_switch'];
		}
		unset($value);
		$pay['credit']['switch'] = false;
		$pay['delivery']['switch'] = false;
		include $this->template('common/paycenter');
	}
}
