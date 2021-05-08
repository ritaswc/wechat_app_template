<?php
error_reporting(0);
define('IN_MOBILE', true);

if(!empty($_POST)) {
	$out_trade_no = $_POST['out_trade_no'];
	require '../../framework/bootstrap.inc.php';
	load()->web('common');
	load()->classs('coupon');
	if ($_POST['body'] == 'site_store') {
		$setting = setting_load('store_pay');
		$setting['payment'] = $setting['store_pay'];
	} else {
		$_W['uniacid'] = $_W['weid'] = intval($_POST['body']);
		$_W['uniaccount'] = $_W['account'] = uni_fetch($_W['uniacid']);
		$_W['acid'] = $_W['uniaccount']['acid'];
		$setting = uni_setting($_W['uniacid'], array('payment'));
	}
	if(is_array($setting['payment'])) {
		$alipay = $setting['payment']['alipay'];
		if(!empty($alipay)) {
			$prepares = array();
			foreach($_POST as $key => $value) {
				if($key != 'sign' && $key != 'sign_type') {
					$prepares[] = "{$key}={$value}";
				}
			}
			sort($prepares);
			$string = implode($prepares, '&');
			$string .= $alipay['secret'];
			$sign = md5($string);
			if($sign == $_POST['sign']) {
				$_POST['query_type'] = 'notify';
				WeUtility::logging('pay-alipay', var_export($_POST, true));
				$log = table('core_paylog')
					->where(array('uniontid' => $out_trade_no))
					->get();
								if(!empty($log) && $log['status'] == '0' && ($_POST['total_fee'] == $log['card_fee'])) {
					$log['transaction_id'] = $_POST['trade_no'];
					$record = array();
					$record['status'] = '1';
					table('core_paylog')
						->where(array('plid' => $log['plid']))
						->fill($record)
						->save();
					if ($log['is_usecard'] == 1 && !empty($log['encrypt_code'])) {
						$coupon_info = table('coupon')
							->select('id')
							->where(array('id' => $log['card_id']))
							->get();
						$coupon_record = table('coupon_record')
							->where(array(
								'code' => $log['encrypt_code'],
								'status' => '1'
							))
							->get();
						load()->model('activity');
						$status = activity_coupon_use($coupon_info['id'], $coupon_record['id'], $log['module']);
					}

					$site = WeUtility::createModuleSite($log['module']);
					if(!is_error($site)) {
						$method = 'payResult';
						if (method_exists($site, $method)) {
							$ret = array();
							$ret['weid'] = $log['weid'];
							$ret['uniacid'] = $log['uniacid'];
							$ret['result'] = 'success';
							$ret['type'] = $log['type'];
							$ret['from'] = 'notify';
							$ret['tid'] = $log['tid'];
							$ret['uniontid'] = $log['uniontid'];
							$ret['transaction_id'] = $log['transaction_id'];
							$ret['user'] = $log['openid'];
							$ret['fee'] = $log['fee'];
							$ret['is_usecard'] = $log['is_usecard'];
							$ret['card_type'] = $log['card_type'];
							$ret['card_fee'] = $log['card_fee'];
							$ret['card_id'] = $log['card_id'];
							$site->$method($ret);
							exit('success');
						}
					}
				}
			}
		}
	}
}
exit('fail');
