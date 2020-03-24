<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');
load()->model('statistics');

$dos = array('display', 'get_account_api', 'get_module_api');
$do = in_array($do, $dos) ? $do : 'display';

permission_check_account_user('statistics_visit_app');
$support_type = array(
	'time' => array('today', 'week', 'month', 'daterange'),
	'divide' => array('bysum', 'byavg', 'byhighest'),
);

if ('display' == $do) {
	$today = stat_visit_app_byuniacid('today');
	$yesterday = stat_visit_app_byuniacid('yesterday');
	$today_module_api = stat_all_visit_statistics('current_account', $today);
	$yesterday_module_api = stat_all_visit_statistics('current_account', $yesterday);
	template('statistics/app-display');
}

if ('get_account_api' == $do) {
	$data = array();
	$type = trim($_GPC['time_type']);
	$divide_type = trim($_GPC['divide_type']);
	if (!in_array($type, $support_type['time']) || !in_array($divide_type, $support_type['divide'])) {
		iajax(-1, '参数错误！');
	}
	$daterange = array();
	if (!empty($_GPC['daterange'])) {
		$daterange = array(
			'start' => date('Ymd', strtotime($_GPC['daterange']['startDate'])),
			'end' => date('Ymd', strtotime($_GPC['daterange']['endDate'])),
		);
	}
	$result = stat_visit_app_bydate($type, '', $daterange);
	if ('today' == $type) {
		$data_x = array(date('Ymd'));
	}
	if ('week' == $type) {
		$data_x = stat_date_range(date('Ymd', strtotime('-7 days')), date('Ymd'));
	}
	if ('month' == $type) {
		$data_x = stat_date_range(date('Ymd', strtotime('-30 days')), date('Ymd'));
	}
	if ('daterange' == $type) {
		$data_x = stat_date_range($daterange['start'], $daterange['end']);
	}
	if (empty($result)) {
		foreach ($data_x as $val) {
			$data_y[] = 0;
		}
		iajax(0, array('data_x' => $data_x, 'data_y' => $data_y));
	}
	foreach ($data_x as $key => $data) {
		foreach ($result as $val) {
			if (strtotime($val['date']) != strtotime($data)) {
				continue;
			}
			if ('bysum' == $divide_type) {
				$data_y[$key] = $val['count'];
			} elseif ('byavg' == $divide_type) {
				$data_y[$key] = $val['avg'];
			} elseif ('byhighest' == $divide_type) {
				$data_y[$key] = $val['highest'];
			}
		}
		if (empty($data_y[$key])) {
			$data_y[$key] = 0;
		}
	}
	iajax(0, array('data_x' => $data_x, 'data_y' => $data_y));
}

if ('get_module_api' == $do) {
	$modules = array();
	$data = array();
	$modules_info = stat_modules_except_system();
	if (in_array(FRAME, array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH))) {
		array_unshift($modules_info, array('name' => 'wesite', 'title' => '微站'));
	} else {
		array_unshift($modules_info, array('name' => 'wesite', 'title' => '其他'));
	}
	foreach ($modules_info as $info) {
		$modules[] = mb_substr($info['title'], 0, 5, 'utf-8');
	}

	$type = trim($_GPC['time_type']);
	$divide_type = trim($_GPC['divide_type']);
	if (!in_array($type, $support_type['time']) || !in_array($divide_type, $support_type['divide'])) {
		iajax(-1, '参数错误！');
	}
	$daterange = array();
	if (!empty($_GPC['daterange'])) {
		$daterange = array(
			'start' => date('Ymd', strtotime($_GPC['daterange']['startDate'])),
			'end' => date('Ymd', strtotime($_GPC['daterange']['endDate'])),
		);
	}

	$result = stat_visit_app_byuniacid($type, '', $daterange);
	if (empty($result)) {
		foreach ($modules_info as $module) {
			$data[] = 0;
		}
		iajax(0, array('data_x' => $data, 'data_y' => $modules));
	}
	foreach ($modules_info as $module) {
		$have_count = false;
		foreach ($result as $val) {
			if ($module['name'] == $val['module']) {
				if ('bysum' == $divide_type) {
					$data[] = $val['count'];
				} elseif ('byavg' == $divide_type) {
					$data[] = $val['avg'];
				} elseif ('byhighest' == $divide_type) {
					$data[] = $val['highest'];
				}
				$have_count = true;
			}
		}
		if (empty($have_count)) {
			$data[] = 0;
		}
	}
	iajax(0, array('data_x' => $data, 'data_y' => $modules));
}