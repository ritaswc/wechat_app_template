<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class PaycenterModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		$rid = $this->rule;
		$reply = table('wxcard_reply')
			->where(array('rid' => $rid))
			->orderby('RAND()')
			->get();
		if (empty($reply)) {
			return false;
		}
		load()->classs('weixin.account');
		load()->classs('coupon');
		$coupon = new coupon($_W['acid']);
		if (is_error($coupon)) {
			$this->error($reply, $coupon['message']);
			die;
		}
		$card = $coupon->BuildCardExt($reply['cid']);
		if (is_error($card)) {
			$this->error($reply, $card['message']);
			die;
		}
		$data = array(
			'touser' => $_W['openid'],
			'msgtype' => 'wxcard',
			'wxcard' => array(
				'card_id' => $card['card_id'],
				'card_ext' => $card['card_ext'],
			),
		);
		$acc = WeAccount::createByUniacid();
		$status = $acc->sendCustomNotice($data);
		if (is_error($status)) {
			$this->error($reply, $status['message']);
			die;
		}
		if (!empty($reply['success'])) {
			return $this->respText($reply['success']);
			die;
		}

		return true;
	}

	public function error($reply, $msg) {
		if (empty($reply['error'])) {
			if (empty($msg)) {
				return true;
			}

			return $this->respText($msg);
		} else {
			return $this->respText($reply['error']);
		}
	}
}
