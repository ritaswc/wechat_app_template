<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class CoreModuleSite extends WeModuleSite {
	public function doMobilePaymethod() {
		global $_W, $_GPC;
		$params = array(
			'fee' => floatval($_GPC['fee']),
			'tid' => $_GPC['tid'],
			'module' => $_GPC['module'],
		);
		if (empty($params['tid']) || empty($params['fee']) || empty($params['module'])) {
			message(error(1, '支付参数不完整'));
		}
				if ($params['fee'] <= 0) {
			$notify_params = array(
				'form' => 'return',
				'result' => 'success',
				'type' => '',
				'tid' => $params['tid'],
			);
			$site = WeUtility::createModuleSite($params['module']);
			$method = 'payResult';
			if (method_exists($site, $method)) {
				$site->$method($notify_params);
				message(error(-1, '支付成功'));
			}
		}
		$log = table('core_paylog')
			->searWithUniacid($_W['uniacid'])
			->SearWithModule($params['module'])
			->searWithTid($params['tid'])
			->get();
		if (empty($log)) {
			$log = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $_W['member']['uid'],
				'module' => $params['module'],
				'tid' => $params['tid'],
				'fee' => $params['fee'],
				'card_fee' => $params['fee'],
				'status' => '0',
				'is_usecard' => '0',
			);
			table('core_paylog')->fill($log)->save();
		}
		if ('1' == $log['status']) {
			message(error(1, '订单已经支付'));
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if (!is_array($setting['payment'])) {
			message(error(1, '暂无有效支付方式'));
		}
		$pay = $setting['payment'];
		if (empty($_W['member']['uid'])) {
			$pay['credit']['switch'] = false;
		}
		if (!empty($pay['credit']['switch'])) {
			$credtis = mc_credit_fetch($_W['member']['uid']);
		}

		include $this->template('pay');
	}

	
	public function doMobilePay() {
		global $_W, $_GPC;

		$moduels = uni_modules();
		$params = $_POST;

		if (empty($params) || !array_key_exists($params['module'], $moduels)) {
			message(error(1, '模块不存在'), '', 'ajax', true);
		}

		$setting = uni_setting($_W['uniacid'], 'payment');
		$dos = array();
		if (!empty($setting['payment']['credit']['pay_switch'])) {
			$dos[] = 'credit';
		}
		if (!empty($setting['payment']['alipay']['pay_switch'])) {
			$dos[] = 'alipay';
		}
		if (!empty($setting['payment']['wechat']['pay_switch'])) {
			$dos[] = 'wechat';
		}
		if (!empty($setting['payment']['delivery']['pay_switch'])) {
			$dos[] = 'delivery';
		}
		if (!empty($setting['payment']['unionpay']['pay_switch'])) {
			$dos[] = 'unionpay';
		}
		if (!empty($setting['payment']['baifubao']['pay_switch'])) {
			$dos[] = 'baifubao';
		}
		$type = in_array($params['method'], $dos) ? $params['method'] : '';
		if (empty($type)) {
			message(error(1, '暂无有效支付方式,请联系商家'), '', 'ajax', true);
		}
		$moduleid = table('modules')
			->where(array('name' => $params['module']))
			->getcolumn('mid');
		$moduleid = empty($moduleid) ? '000000' : sprintf('%06d', $moduleid);
		$uniontid = date('YmdHis') . $moduleid . random(8, 1);

		$paylog = table('core_paylog')
			->where(array('uniacid' => $uniacid))
			->searchWithModule($params['module'])
			->searchWithTid($params['tid'])
			->get();
		if (empty($paylog)) {
			$paylog = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $_W['member']['uid'],
				'type' => $type,
				'module' => $params['module'],
				'tid' => $params['tid'],
				'uniontid' => $uniontid,
				'fee' => $params['fee'],
				'card_fee' => $params['fee'],
				'status' => '0',
				'is_usecard' => '0',
			);
			table('core_paylog')->fill($paylog)->save();
			$paylog['plid'] = pdo_insertid();
		}
		if (!empty($paylog) && '0' != $paylog['status']) {
			message(error(1, '这个订单已经支付成功, 不需要重复支付.'), '', 'ajax', true);
		}
		if (!empty($paylog) && empty($paylog['uniontid'])) {
			table('core_paylog')
				->where(array('plid' => $paylog['plid']))
				->fill(array('uniontid' => $uniontid))
				->save();
		}
		$paylog['title'] = $params['title'];
		if (intval($_GPC['iswxapp'])) {
			message(error(2, $_W['siteroot'] . "app/index.php?i={$_W['uniacid']}&c=wxapp&a=home&do=go_paycenter&title={$params['title']}&plid={$paylog['plid']}"), '', 'ajax', true);
		}

		if ('wechat' == $params['method']) {
			return $this->doMobilePayWechat($paylog);
		} elseif ('alipay' == $params['method']) {
			return $this->doMobilePayAlipay($paylog);
		} else {
			$params['tid'] = $paylog['plid'];
			$sl = base64_encode(json_encode($params));
			$auth = sha1($sl . $_W['uniacid'] . $_W['config']['setting']['authkey']);
			message(error(0, $_W['siteroot'] . "/payment/{$type}/pay.php?i={$_W['uniacid']}&auth={$auth}&ps={$sl}"), '', 'ajax', true);
			exit();
		}
	}

	private function doMobilePayWechat($paylog = array()) {
		global $_W;
		load()->model('payment');

		table('core_paylog')
			->where(array('plid' => $paylog['plid']))
			->fill(array(
				'openid' => $_W['openid'],
				'tag' => iserializer(array('acid' => $_W['acid'], 'uid' => $_W['member']['uid'])),
			))
			->save();
		$_W['uniacid'] = $paylog['uniacid'];

		$setting = uni_setting($_W['uniacid'], array('payment'));
		$wechat_payment = $setting['payment']['wechat'];

		$account = table('account_wechats')
			->where(array('acid' => $wechat_payment['account']))
			->get();
		$wechat_payment['appid'] = $account['key'];
		$wechat_payment['secret'] = $account['secret'];

		$params = array(
			'tid' => $paylog['tid'],
			'fee' => $paylog['card_fee'],
			'user' => $paylog['openid'],
			'title' => urldecode($paylog['title']),
			'uniontid' => $paylog['uniontid'],
		);
		if (PAYMENT_WECHAT_TYPE_SERVICE == intval($wechat_payment['switch']) || PAYMENT_WECHAT_TYPE_BORROW == intval($wechat_payment['switch'])) {
			if (!empty($_W['openid'])) {
				$params['sub_user'] = $_W['openid'];
				$wechat_payment_params = wechat_proxy_build($params, $wechat_payment);
			} else {
				$params['tid'] = $paylog['plid'];
								$params['title'] = urlencode($params['title']);
				$sl = base64_encode(json_encode($params));
				$auth = sha1($sl . $paylog['uniacid'] . $_W['config']['setting']['authkey']);

				$callback = urlencode($_W['siteroot'] . "payment/wechat/pay.php?i={$_W['uniacid']}&auth={$auth}&ps={$sl}");
				$proxy_pay_account = payment_proxy_pay_account();
				if (!is_error($proxy_pay_account)) {
					$forward = $proxy_pay_account->getOauthCodeUrl($callback, 'we7sid-' . $_W['session_id']);
					message(error(2, $forward), $forward, 'ajax');
					exit;
				}
			}
		} else {
			unset($wechat_payment['sub_mch_id']);
			$wechat_payment_params = wechat_build($params, $wechat_payment);
		}
		if (is_error($wechat_payment_params)) {
			message($wechat_payment_params, '', 'ajax', true);
		} else {
			message(error(0, $wechat_payment_params), '', 'ajax', true);
		}
	}

	private function doMobilePayAlipay($paylog = array()) {
		global $_W;

		load()->model('payment');
		load()->func('communication');

		$_W['uniacid'] = $paylog['uniacid'];

		$setting = uni_setting($_W['uniacid'], array('payment'));
		$params = array(
			'tid' => $paylog['tid'],
			'fee' => $paylog['card_fee'],
			'user' => $paylog['openid'],
			'title' => urldecode($paylog['title']),
			'uniontid' => $paylog['uniontid'],
		);
		$alipay_payment_params = alipay_build($params, $setting['payment']['alipay']);
		if ($alipay_payment_params['url']) {
			message(error(0, $alipay_payment_params['url']), '', 'ajax', true);
			exit();
		}
	}

	public function doMobileDetail() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$row = table('news_reply')->getById($id);
		$createtime = $row['createtime'];
		if (!empty($row['url'])) {
			header('Location: ' . $row['url']);
			exit;
		}
				if (!empty($row['media_id']) && 0 != intval($row['media_id'])) {
			$row = table('wechat_news')
				->where(array(
					'attach_id' => $row['media_id'],
					'displayorder' => $row['displayorder']
				))
				->get();
			$row['createtime'] = $createtime;
			if (!empty($row['content_source_url'])) {
				header('Location: ' . $row['content_source_url']);
				exit;
			}
		}
		$row = istripslashes($row);
		$title = $row['title'];
		
		if ('android' == $_W['os'] && 'wechat' == $_W['container'] && $_W['account']['account']) {
			$subscribeurl = "weixin://profile/{$_W['account']['account']}";
		} else {
			$subscribeurl = table('account_wechats')
				->where(array('uniacid' => intval($_W['uniacid'])))
				->getcolumn('subscribeurl');
		}
		include $this->template('detail');
	}
}
