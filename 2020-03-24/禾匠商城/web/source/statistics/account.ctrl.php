<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('statistics');

$dos = array('display', 'app_display', 'get_account_api', 'get_account_app_api', 'get_account_visit');
$do = in_array($do, $dos) ? $do : 'display';

$support_type = array(
		'time' => array('today', 'week', 'month', 'daterange'),
		'divide' => array('bysum', 'byavg', 'byhighest'),
);


	if ('display' == $do) {
		$today = stat_visit_all_bydate('today', array(), true);
		$today = $today['count'];
		$today = !empty($today) ? current($today) : 0;
		$yesterday = stat_visit_all_bydate('yesterday', array(), true);
		$yesterday = $yesterday['count'];
		$yesterday = !empty($yesterday) ? current($yesterday) : 0;
		template('statistics/account');
	}

	if ('app_display' == $do) {
		$today = stat_visit_app_bydate('today', '', array(), true);
		$today = current($today)['count'];
		$today = !empty($today) ? $today : 0;
		$yesterday = stat_visit_all_bydate('yesterday', '', array(), true);
		$yesterday = current($yesterday)['count'];
		$yesterday = !empty($yesterday) ? $yesterday : 0;
		template('statistics/app-account');
	}


if ('get_account_api' == $do) {
	$data = array();
	$time_type = trim($_GPC['time_type']);
	$type = trim($_GPC['type']);
	if (!in_array($time_type, $support_type['time'])) {
		iajax(-1, '参数错误！');
	}
	$daterange = array();
	if (!empty($_GPC['daterange'])) {
		$daterange = array(
			'start' => date('Ymd', strtotime($_GPC['daterange']['startDate'])),
			'end' => date('Ymd', strtotime($_GPC['daterange']['endDate'])),
		);
	}
	if ('web' == $type) {
		$all_result = stat_visit_web_bydate($time_type, $daterange, true);
	} elseif ('app' == $type) {
		$all_result = array();
		$visit_info = stat_visit_info('app', $time_type, '', $daterange, true);
		if (!empty($visit_info)) {
			foreach ($visit_info as $visit) {
				$all_result['count'][$visit['date']] += $visit['count'];
				$all_result['ip_count'][$visit['date']] += $visit['ip_count'];
			}
		}
	} else {
		$all_result = stat_visit_all_bydate($time_type, $daterange, true);
	}
	$result = $all_result['count'];
	$ip_visit_result = $all_result['ip_count'];
	if ('today' == $time_type) {
		$data_x = array(date('Ymd'));
	}
	if ('week' == $time_type) {
		$data_x = stat_date_range(date('Ymd', strtotime('-6 days')), date('Ymd'));
	}
	if ('month' == $time_type) {
		$data_x = stat_date_range(date('Ymd', strtotime('-29 days')), date('Ymd'));
	}
	if ('daterange' == $time_type) {
		$data_x = stat_date_range($daterange['start'], $daterange['end']);
	}
	if (empty($result)) {
		foreach ($data_x as $val) {
			$data_y[] = 0;
		}
	} else {
		foreach ($data_x as $key => $data) {
			foreach ($result as $date_key => $val) {
				if (strtotime($date_key) != strtotime($data)) {
					continue;
				}
				$data_y[$key] = $val;
			}
			if (empty($data_y[$key])) {
				$data_y[$key] = 0;
			}
		}
	}

	if (empty($ip_visit_result)) {
		foreach ($data_x as $val) {
			$data_y_ip[] = 0;
		}
	} else {
		foreach ($data_x as $key => $data) {
			foreach ($ip_visit_result as $ip_date_key => $ip_val) {
				if (strtotime($ip_date_key) != strtotime($data)) {
					continue;
				}
				$data_y_ip[$key] = $ip_val;
			}
			if (empty($data_y_ip[$key])) {
				$data_y_ip[$key] = 0;
			}
		}
	}
	if (empty($result) && empty($ip_visit_result)) {
		iajax(0, array('data_x' => $data_x, 'data_y' => $data_y, 'data_y_ip' => $data_y_ip));
	}

	iajax(0, array('data_x' => $data_x, 'data_y' => $data_y, 'data_y_ip' => $data_y_ip));
}

if ('get_account_visit' == $do) {
	$page = max(1, intval($_GPC['page']));
	$size = max(10, intval($_GPC['size']));
	$type = safe_gpc_string($_GPC['type']);
	$start_time = date('Ymd', strtotime($_GPC['start_time']));
	$end_time = date('Ymd', strtotime($_GPC['end_time']) + 86400);
	if (empty($start_time) || empty($end_time)) {
		iajax(1, '参数有误');
	}
	$account_table = table('account');
	$account_table->searchWithUniAccount();
	$account_table->select(array('a.*', 'b.name'));
	if (!empty($type) && $type == 'app') {
		$account_table->where('a.isdeleted', 0);
	}
	$accounts = $account_table
		->searchWithPage($page, $size)
		->where(array('a.type' => array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH, ACCOUNT_TYPE_APP_NORMAL, ACCOUNT_TYPE_APP_AUTH, ACCOUNT_TYPE_APP_PLATFORM)))
		->where(function ($query) {
			$query->where(array('a.endtime' => 0))
				->whereor(array('a.endtime' => USER_ENDTIME_GROUP_UNLIMIT_TYPE))
				->whereor(array('a.endtime >=' => TIMESTAMP));
		})
		->orderby(array(
			'b.rank' => 'DESC',
			'a.uniacid' => 'DESC'
		))
		->getall('uniacid');
	if (empty($accounts)) {
		iajax(0, array());
	}
	$total_account = $account_table->getLastQueryTotal();
	$tota_visit = 0;
	$account_stat = array();
	$where = array(
		'uniacid IN' => array_keys($accounts),
		'date >=' => $start_time,
		'date <=' => $end_time
	);
	if (!empty($type) && $type == 'app') {
		$where['type'] = 'app';
	}
	$visit_data = table('stat_visit')
		->select(array('uniacid', 'count'))
		->where($where)
		->getall();
	foreach ($visit_data as $item) {
		$tota_visit += $item['count'];
		if (!empty($accounts[$item['uniacid']])) {
			$account_stat[$item['uniacid']]['total'] += $item['count'];
			$account_stat[$item['uniacid']]['name'] = $accounts[$item['uniacid']]['name'];
		}
	}
	foreach ($accounts as $uniacid => $account) {
		if (!empty($account_stat[$uniacid])) {
			$accounts[$uniacid] = $account_stat[$uniacid];
		} else {
			$accounts[$uniacid] = array('total' => 0, 'name' => $account['name']);
		}
	}
	iajax(0, array(
		'total_account' => $total_account,
		'total_visit' => $tota_visit,
		'list' => array_values($accounts),
	));
}