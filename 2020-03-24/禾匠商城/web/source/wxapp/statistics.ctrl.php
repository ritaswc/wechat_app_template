<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('statistics');

$dos = array('display', 'get_visit_api');
$do = in_array($do, $dos) ? $do : 'display';

permission_check_account_user('statistics_visit_wxapp');

if ('display' == $do) {
		miniapp_update_daily_visittrend();
	$yesterday = date('Ymd', strtotime('-1 days'));
	$yesterday_stat = table('wxapp_general_analysis')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'type' => '2',
			'ref_date' => $yesterday
		))
		->get();
	if (empty($yesterday_stat)) {
		$yesterday_stat = array('session_cnt' => 0, 'visit_pv' => 0, 'visit_uv' => 0, 'visit_uv_new' => 0, 'stay_time_uv' => 0, 'stay_time_session' => 0);
	} else {
		$yesterday_stat['stay_time_uv'] = intval($yesterday_stat['stay_time_uv']);
		$yesterday_stat['stay_time_session'] = intval($yesterday_stat['stay_time_session']);
	}
}

if ('get_visit_api' == $do) {
	$support_type = array(
		'time' => array('today', 'week', 'month', 'daterange'),
		'divide' => array('session_cnt', 'visit_pv', 'visit_uv', 'visit_uv_new', 'stay_time_uv', 'stay_time_session'),
	);
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
	$params = array('uniacid' => $_W['uniacid']);
	switch ($type) {
		case 'week':
			$params['ref_date >'] = date('Ymd', strtotime('-7 days'));
			$params['ref_date <='] = date('Ymd');
			break;
		case 'month':
			$params['ref_date >'] = date('Ymd', strtotime('-30 days'));
			$params['ref_date <='] = date('Ymd');
			break;
		case 'daterange':
			if (empty($daterange)) {
				$daterange = array('start' => date('Ymd', strtotime('-30 days')), 'end' => date('Ymd'));
			}
			$params['ref_date >='] = date('Ymd', strtotime($daterange['start']));
			$params['ref_date <='] = date('Ymd', strtotime($daterange['end']));
			break;
	}
	$result = table('wxapp_general_analysis')
		->where($params)
		->getall('ref_date');
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
	$today = date('Ymd');
	foreach ($data_x as $key => $date) {
		if ($date == $today) {
			continue;
		}
		if (empty($result[$date])) {
			$date_visit = miniapp_insert_date_visit_trend($date);
			$data_y[$key] = empty($date_visit[$divide_type]) ? 0 : $date_visit[$divide_type];
		} else {
			$data_y[$key] = $result[$date][$divide_type];
		}
	}
	iajax(0, array('data_x' => $data_x, 'data_y' => $data_y));
}

template('wxapp/statistics');