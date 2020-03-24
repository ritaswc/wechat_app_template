<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('set', 'list');
$do = in_array($do, $dos) ? $do : 'list';

permission_check_account_user('profile_setting');

if ('set' == $do) {
	$tpl_list = $_GPC['tpl'];
	foreach ($tpl_list as &$tpl) {
		if (!empty($tpl['tpl'])) {
			$inspect_tpl = preg_match('/^[a-zA-Z0-9_-]{43}$/', $tpl['tpl']);
			if (empty($inspect_tpl)) {
				$error = $tpl['name'];
				break;
			}
		}
		unset($tpl['name'], $tpl['help']);
	}
	if (!empty($error)) {
		iajax(1, $error, '');
	} else {
		uni_setting_save('tplnotice', $tpl_list);
		iajax(0, '');
	}
}

$setting = uni_setting_load('tplnotice');
$tpl_setting = $setting['tplnotice'];
if (!is_array($tpl_setting)) {
	$tpl_setting = array();
}
$tpl = array(
	'recharge' => array(
		'tpl' => $tpl_setting['recharge']['tpl'],
		'name' => '会员余额充值',
		'help' => '请在“微信公众平台”选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”会员充值通知“，编号为：“TM00009”的模板。',
	),
	'credit2' => array(
		'tpl' => $tpl_setting['credit2']['tpl'],
		'name' => '会员余额消费',
		'help' => '请在“微信公众平台”选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”余额变更通知“，编号为：“OPENTM207266084”的模板。',
	),
	'credit1' => array(
		'tpl' => $tpl_setting['credit1']['tpl'],
		'name' => '会员积分变更',
		'help' => '请在“微信公众平台”选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”变更通知“，编号为：“OPENTM403182052”的模板。',
	),
	'group' => array(
		'tpl' => $tpl_setting['group']['tpl'],
		'name' => '会员等级变更',
		'help' => '请在“微信公众平台”选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”会员级别变更提醒“，编号为：“TM00891”的模板',
	),
	'nums_plus' => array(
		'tpl' => $tpl_setting['nums_plus']['tpl'],
		'name' => '会员卡计次充值',
		'help' => '请在“微信公众平台”选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”计次充值通知“，编号为：“OPENTM207207134”的模板 ',
	),
	'nums_times' => array(
		'tpl' => $tpl_setting['nums_times']['tpl'],
		'name' => '会员卡计次消费',
		'help' => '请在“微信公众平台”选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”计次消费通知“，编号为：“OPENTM202322532”的模板',
	),
	'times_plus' => array(
		'tpl' => $tpl_setting['times_plus']['tpl'],
		'name' => '会员卡计时充值',
		'help' => '请在“微信公众平台”选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”自动续费成功通知“，编号为：“TM00956”的模板',
	),
	'times_times' => array(
		'tpl' => $tpl_setting['times_times']['tpl'],
		'name' => '会员卡计时消费',
		'help' => '请在“微信公众平台”选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”计时消费通知“，编号为：“OPENTM207847177”的模板',
	),
	'pay_success' => array(
		'tpl' => $tpl_setting['pay_success']['tpl'],
		'name' => '订单支付成功通知',
		'help' => '请在“微信公众平台”选择行业为：“IT科技 - 互联网|电子商务”，添加标题为：”订单支付成功通知“，编号为：“OPENTM207498902”的模板。',
	),
);
template('profile/tplnotice');