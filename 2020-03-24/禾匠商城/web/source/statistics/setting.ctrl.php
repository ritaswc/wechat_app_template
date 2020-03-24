<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('statistics');

$dos = array('display', 'edit_setting');
$do = in_array($do, $dos) ? $do : 'display';
permission_check_account_user('statistics_visit_setting');

$statistics_setting = (array) uni_setting_load(array('statistics'), $_W['uniacid']);
$statistics_setting = $statistics_setting['statistics'];
if ('display' == $do) {
	$highest_visit = empty($statistics_setting['owner']) ? 0 : $statistics_setting['owner'];
	$interval = empty($statistics_setting['interval']) ? 0 : $statistics_setting['interval'];

	$highest_api_visit = empty($statistics_setting['founder']) ? 0 : $statistics_setting['founder'];
	$month_use = 0;
	$stat_visit_teble = table('stat_visit');
	$stat_visit_teble->searchWithGreaterThenDate(date('Ym01'));
	$stat_visit_teble->searchWithLessThenDate(date('Ymt'));
	$stat_visit_teble->searchWithType('app');
	$stat_visit_teble->searchWithUnacid($_W['uniacid']);
	$visit_list = $stat_visit_teble->getall();

	if (!empty($visit_list)) {
		foreach ($visit_list as $key => $val) {
			$month_use += $val['count'];
		}
	}

	$order_num = 0;
	$orders = table('site_store_order')->getApiOrderByUniacid($_W['uniacid']);
	if (!empty($orders)) {
		foreach ($orders as $order) {
			$order_num += $order['duration'] * $order['api_num'] * 10000;
		}
	}

	$api_remain_num = empty($statistics_setting['use']) ? $highest_api_visit + $order_num : ($highest_api_visit + $order_num - $statistics_setting['use']);
	if ($api_remain_num < 0) {
		$api_remain_num = 0;
	}
}
if ('edit_setting' == $do) {
	$type = trim($_GPC['type']);
	$new_highest_visit = intval($_GPC['highest_visit']);
	$new_interval = intval($_GPC['interval']);
	if (!empty($statistics_setting)) {
		$highest_visit = $statistics_setting;
		if ('highest_visit' == $type) {
			$highest_visit['owner'] = $new_highest_visit;
		} elseif ('interval' == $type) {
			$highest_visit['interval'] = $new_interval;
		}
	} else {
		if ('highest_visit' == $type) {
			$highest_visit = array('owner' => $new_highest_visit);
		} elseif ('interval' == $type) {
			$highest_visit = array('interval' => $new_interval);
		}
	}
	$result = uni_setting_save('statistics', iserializer($highest_visit));
	if (!empty($result)) {
		cache_delete(cache_system_key('unisetting', array('uniacid' => $_W['uniacid'])));
		cache_delete(cache_system_key('statistics', array('uniacid' => $uniacid)));
		iajax(0, '修改成功！');
	} else {
		iajax(-1, '修改失败！');
	}
}
template('statistics/setting');