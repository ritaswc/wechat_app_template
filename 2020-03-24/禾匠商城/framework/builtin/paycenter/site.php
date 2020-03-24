<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
class PaycenterModuleSite extends WeModuleSite {
	public function __construct() {
		global $_W, $_GPC;
		load()->model('paycenter');
		if ('pay' != $_GPC['do'] && 'consume' != $_GPC['do']) {
			$session = json_decode(base64_decode($_GPC['_pc_session']), true);
			if (is_array($session)) {
				load()->model('user');
				$user = user_single(array('uid' => $session['uid']));
				if (is_array($user) && $session['hash'] === $user['hash']) {
					$clerk = table('activity_clerks')->getByUid($user['uid'], $_W['uniacid']);
					if (empty($clerk)) {
						message('您没有管理该店铺的权限', referer(), 'error');
					}
					$_W['uid'] = $user['uid'];
					$_W['username'] = $user['username'];
					$_W['user'] = $user;
				} else {
					isetcookie('_pc_session', false, -100);
				}
				unset($user);
			}
			if (empty($_W['user']) && $_W['openid'] && '1' != $_GPC['_wechat_logout']) {
				$clerk = table('activity_clerks')->getByOpenid($_W['openid'], $_W['uniacid']);
				if (!empty($clerk)) {
					$user = table('users')->where(array('uid' => $clerk['uid']))->get();
					if (!empty($user)) {
						$cookie = array();
						$cookie['uid'] = $user['uid'];
						$cookie['username'] = $user['username'];
						$cookie['hash'] = md5($user['password'] . $user['salt']);
						$session = base64_encode(json_encode($cookie));
						isetcookie('_pc_session', $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
						$_W['uid'] = $user['uid'];
						$_W['username'] = $user['username'];
						$_W['user'] = $user;
					}
				}
			}
		}
	}

	public function doMobileLogin() {
		global $_W, $_GPC;
		if (!empty($_W['user'])) {
			header('Location:' . $this->createMobileUrl('home'));
			die;
		}
		if ($_W['isajax']) {
			load()->model('user');
			$user['username'] = trim($_GPC['username']);
			$user['password'] = trim($_GPC['password']);

			$user = user_single($user);
			if (empty($user)) {
				message(error(-1, '账号或密码错误'), '', 'ajax');
			}
			if (1 == $user['status']) {
				message(error(-1, '您的账号正在审核或是已经被系统禁止，请联系网站管理员解决'), '', 'ajax');
			}
			$clerk = table('activity_clerks')->getByUid($user['uid'], $_W['uniacid']);
			if (empty($clerk)) {
				message(error(-1, '您没有管理该店铺的权限'), '', 'ajax');
			}
			$cookie = array();
			$cookie['uid'] = $user['uid'];
			$cookie['hash'] = $user['hash'];
			$session = base64_encode(json_encode($cookie));
			isetcookie('_pc_session', $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
			message(error(0, ''), '', 'ajax');
		}
		include $this->template('login');
	}

	public function doMobileLogout() {
		isetcookie('_pc_session', '', -10000);
		isetcookie('_wechat_logout', '1', 180);
		$forward = $_GPC['forward'];
		if (empty($forward)) {
			$forward = './?refersh';
		}
		header('Location:' . $this->createMobileUrl('login'));
		die;
	}

	public function doMobileHome() {
		global $_W, $_GPC;
		paycenter_check_login();
		$user_permission = permission_account_user('system');
		$today_revenue = $this->revenue(0);
		$yesterday_revenue = $this->revenue(-1);
		$seven_revenue = $this->revenue(-7);
		include $this->template('home');
	}

	
	public function revenue($period) {
		global $_W;
		if ('0' == $period) {
			$starttime = strtotime(date('Y-m-d'));
			$endtime = $starttime + 86400;
		} else {
			$starttime = strtotime(date('Y-m-d', strtotime($period . 'day')));
			$endtime = strtotime(date('Y-m-d'));
		}
		$revenue = table('paycenter_order')
			->where(array(
				'uniacid' =>  $_W['uniacid'],
				'status' => 1,
				'paytime >=' => $starttime,
				'paytime <=' => $endtime,
				'clerk_id' => intval($_W['user']['clerk_id'])
			))
			->getcolumn('SUM(final_fee)');
		return floatval($revenue);
	}

	public function doMobilePay() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$order = table('paycenter_order')->getById($id, $_W['uniacid']);

		if (empty($order)) {
			message('订单不存在或已删除', '', 'error');
		}
		if (1 == $order['status']) {
			message('该订单已付款', '', 'error');
		}
		if (!empty($_W['member']['uid']) || !empty($_W['fans'])) {
			$update = array(
				'uid' => $_W['member']['uid'],
				'openid' => $_W['openid'],
				'nickname' => $_W['fans']['nickname'],
			);
			table('paycenter_order')
				->where(array(
					'uniacid' => $_W['uniacid'],
					'id' => $id
				))
				->fill($update)
				->save();
			$order['uid'] = $_W['member']['uid'];
		}
		$params['module'] = 'paycenter_order';
		$params['tid'] = $order['id'];
		$params['ordersn'] = $order['id'];
		$params['user'] = $order['uid'];
		$params['fee'] = $order['final_fee'];
		$params['title'] = $_W['account']['name'] . $order['body'] ? $order['body'] : '收银台收款';
		$this->pay($params);
	}

	public function payResult($params) {
		global $_W;
		if ('success' == $params['result'] && 'notify' == $params['from']) {
			$order = table('paycenter_order')->getById($params['tid'], $_W['uniacid']);
			if (!empty($order)) {
				if (!empty($params['tag'])) {
					$params['tag'] = iunserializer($params['tag']);
				}
				$data = array(
					'type' => $params['type'],
					'trade_type' => strtolower($params['trade_type']),
					'status' => 1,
					'paytime' => TIMESTAMP,
					'uniontid' => $params['tag']['uniontid'],
					'transaction_id' => $params['tag']['transaction_id'],
					'follow' => intval($params['follow']),
					'final_fee' => $params['card_fee'],
				);
				if ('credit' == $params['type']) {
					$data['credit2'] = $params['card_fee'];
				} else {
					$data['cash'] = $params['card_fee'];
				}
				if (1 == $params['is_usecard']) {
					$discount_fee = $order['fee'] - $params['card_fee'];
					$data['remark'] = "使用优惠券减免{$discount_fee}元";
				}
				table('paycenter_order')
					->where(array(
						'id' => $params['tid'],
						'uniacid' => $_W['uniacid']
					))
					->fill($data)
					->save();

				$cash_data = array(
						'uniacid' => $_W['uniacid'],
						'uid' => $order['uid'],
						'fee' => $order['fee'],
						'final_fee' => $order['final_fee'],
						'credit1' => $order['credit1'],
						'credit1_fee' => $order['credit1_fee'],
						'credit2' => $order['credit2'],
						'cash' => $params['card_fee'],
						'final_cash' => $params['card_fee'],
						'return_cash' => 0,
						'remark' => $order['remark'],
						'clerk_id' => $order['clerk_id'],
						'store_id' => $order['store_id'],
						'clerk_type' => $order['clerk_type'],
						'createtime' => TIMESTAMP,
				);
				table('mc_cash_record')->fill($cash_data)->save();
			}
		}
		if ('success' == $params['result'] && 'return' == $params['from']) {
			message('支付成功！', $this->createMobileUrl('paydetail', array('id' => $params['tid'])), 'success');
		}
	}

	public function doMobilePayDetail() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$order = table('paycenter_order')->getById($id, $_W['uniacid']);
		if (empty($order)) {
			message('订单不存在或已删除', '', 'error');
		}
		if ($order['store_id'] > 0) {
			$store = table('activity_stores')
				->select('business_name')
				->where(array('id' => $order['store_id']))
				->get();
		}
		include $this->template('paydetail');
	}

	public function doMobileSelfpay() {
		global $_W, $_GPC;
		if (checksubmit()) {
			$fee = trim($_GPC['fee']) ? trim($_GPC['fee']) : message('收款金额有误', '', 'error');
			$body = trim($_GPC['body']) ? trim($_GPC['body']) : '收银台收款' . trim($_GPC['fee']);
			$openid = trim($_GPC['openid']) ? trim($_GPC['openid']) : message('用户信息错误', '', 'error');
			$clerk = table('activity_clerks')->getById(intval($_GPC['clerk_id']), $_W['uniacid']);
			$data = array(
				'uniacid' => $_W['uniacid'],
				'openid' => $openid,
				'nickname' => trim($_GPC['nickname']),
				'uid' => $_W['member']['uid'],
				'clerk_id' => $clerk['id'],
				'clerk_type' => 3,
				'store_id' => $clerk['storeid'],
				'body' => $body,
				'fee' => $fee,
				'final_fee' => $fee,
				'credit_status' => 1,
				'createtime' => TIMESTAMP,
			);
			table('paycenter_order')->fill($data)->save();
			$id = pdo_insertid();
			header('location:' . $this->createMobileUrl('pay', array('id' => $id)));
			die;
		}
		$fans = mc_oauth_userinfo();
		if (is_error($fans) || empty($fans)) {
			message('获取粉丝信息失败', '', 'error');
		}
		include $this->template('selfpay');
	}

	public function doMobileConsume() {
		global $_GPC, $_W;
		$url = murl('entry', array('m' => 'we7_coupon', 'do' => 'consume', 'card_id' => trim($_GPC['card_id']), 'encrypt_code' => trim($_GPC['encrypt_code']), 'openid' => trim($_GPC['openid'])));
		header("Location: $url");
		exit;
	}
}