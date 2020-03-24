<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
require '../../app/common/bootstrap.app.inc.php';
load()->app('common');
load()->app('template');
load()->model('payment');

$sl = $_GPC['ps'];
$payopenid = $_GPC['payopenid'];
$params = @json_decode(base64_decode($sl), true);
if($_GPC['done'] == '1') {
	$log = table('core_paylog')
        ->where(array('plid' => $params['tid']))
        ->get();
	if(!empty($log) && !empty($log['status'])) {
		if (!empty($log['tag'])) {
			$tag = iunserializer($log['tag']);
			$log['uid'] = $tag['uid'];
		}
		$site = WeUtility::createModuleSite($log['module']);
		if(!is_error($site)) {
			$method = 'payResult';
			if (method_exists($site, $method)) {
				$ret = array();
				$ret['weid'] = $log['uniacid'];
				$ret['uniacid'] = $log['uniacid'];
				$ret['result'] = 'success';
				$ret['type'] = $log['type'];
				$ret['from'] = 'return';
				$ret['tid'] = $log['tid'];
				$ret['uniontid'] = $log['uniontid'];
				$ret['user'] = $log['openid'];
				$ret['fee'] = $log['fee'];
				$ret['tag'] = $tag;
				$ret['is_usecard'] = $log['is_usecard'];
				$ret['card_type'] = $log['card_type'];
				$ret['card_fee'] = $log['card_fee'];
				$ret['card_id'] = $log['card_id'];
				exit($site->$method($ret));
			}
		}
	}
}

$log = table('core_paylog')
    ->where(array('plid' => $params['tid']))
    ->get();
if(!empty($log) && $log['status'] != '0') {
	exit('这个订单已经支付成功, 不需要重复支付.');
}
$auth = sha1($sl . $log['uniacid'] . $_W['config']['setting']['authkey']);
if($auth != $_GPC['auth']) {
	exit('参数传输错误.');
}

$setting = uni_setting($_W['uniacid'], array('payment'));

if (!empty($_GPC['code'])) {
	$proxy_pay_account = payment_proxy_pay_account();
	$oauth = $proxy_pay_account->getOauthInfo($_GPC['code']);
	if (!empty($oauth['openid'])) {
		$log['openid'] = $oauth['openid'];
	}
}

$_W['uniacid'] = $log['uniacid'];
$_W['openid'] = $log['openid'];

if(!is_array($setting['payment'])) {
	exit('没有设定支付参数.');
}

$wechat = $setting['payment']['wechat'];
$row = table('account_wechats')
    ->select(array('key', 'secret'))
    ->where(array('acid' => $wechat['account']))
    ->get();
$wechat['appid'] = $row['key'];
$wechat['secret'] = $row['secret'];
$wechat['openid'] = $payopenid;
$params = array(
	'tid' => $log['tid'],
	'fee' => $log['card_fee'],
	'user' => $log['openid'],
	'title' => urldecode($params['title']),
	'uniontid' => $log['uniontid'],
);
if (intval($wechat['switch']) == 3 || intval($wechat['switch']) == 2) {
	$wOpt = wechat_proxy_build($params, $wechat);
} else {
	unset($wechat['sub_mch_id']);
	$wOpt = wechat_build($params, $wechat);
}
if (is_error($wOpt)) {
	if ($wOpt['message'] == 'invalid out_trade_no' || $wOpt['message'] == 'OUT_TRADE_NO_USED') {
		$id = date('YmdH');
		table('core_paylog')
            ->where(array('plid' => $log['plid']))
            ->fill(array('plid' => $id))
            ->save();
		pdo_query("ALTER TABLE ".tablename('core_paylog')." auto_increment = ".($id+1).";");
		message("抱歉，发起支付失败，系统已经修复此问题，请重新尝试支付。");
	}
	message("抱歉，发起支付失败，具体原因为：“{$wOpt['errno']}:{$wOpt['message']}”。请及时联系站点管理员。");
	exit;
}
?>
<script type="text/javascript">
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
		WeixinJSBridge.invoke('getBrandWCPayRequest', {
			'appId' : '<?php echo $wOpt['appId'];?>',
			'timeStamp': '<?php echo $wOpt['timeStamp'];?>',
			'nonceStr' : '<?php echo $wOpt['nonceStr'];?>',
			'package' : '<?php echo $wOpt['package'];?>',
			'signType' : '<?php echo $wOpt['signType'];?>',
			'paySign' : '<?php echo $wOpt['paySign'];?>'
		}, function(res) {
			if(res.err_msg == 'get_brand_wcpay_request:ok') {
				location.search += '&done=1';
			} else {
//				alert('启动微信支付失败, 请检查你的支付参数. 详细错误为: ' + res.err_msg);
				history.go(-1);
			}
		});
	}, false);
</script>