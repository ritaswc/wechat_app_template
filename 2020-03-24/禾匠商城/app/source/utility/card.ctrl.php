<?php
defined('IN_IA') or exit('Access Denied');
global $_GPC;
$card = table('mc_card')->where(array('uniacid' => safe_gpc_int($_GPC['uniacid'])))->get();
if (!empty($card)) {
	if ($card['source'] == 2) {
		$code = table('coupon_record')
			->where(array(
				'uniacid' => safe_gpc_int($_GPC['uniacid']),
				'card_id' => $card['card_id']
			))
			->getcolumn('code');
		$status = empty($code) ? 3 : 1;
		if (empty($code)) {
			load()->classs('coupon');
			$coupon_api = new coupon($_GPC['acid']);
			$card_id = trim($card['card_id']);
			$result = $coupon_api->BuildCardExt($card['card_id'], '', 'membercard');
			message(error(3, array('card_ext' => $result['card_ext'], 'card_id' => $card['card_id'])), '', 'ajax');
		} else {
			message(error(1, array('code' => $code, 'card_id' => $card['card_id'])), '', 'ajax');
		}
	}
	if ($card['source'] == 1) {
		message(error(2), '', 'ajax');
	}
} else {
	message(error(0, $_W['uniacid']), '', 'ajax');
}