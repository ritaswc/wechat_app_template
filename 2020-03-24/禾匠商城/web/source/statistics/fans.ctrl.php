<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('statistics');

$dos = array('display', 'get_fans_api');
$do = in_array($do, $dos) ? $do : 'display';
$support_type = array(
	'time' => array('week', 'month', 'daterange'),
	'divide' => array('bynew', 'bycancel', 'bytotal'),
);

if ('display' == $do) {
	$today_stat = pdo_get('stat_fans', array('date' => date('Ymd'), 'uniacid' => $_W['uniacid']));
	$yesterday_stat = pdo_get('stat_fans', array('date' => date('Ymd', strtotime('-1 days')), 'uniacid' => $_W['uniacid']));
	template('statistics/fans-display');
}

if ('get_fans_api' == $do) {
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
			$params['date >'] = date('Ymd', strtotime('-7 days'));
			$params['date <='] = date('Ymd');
			break;
		case 'month':
			$params['date >'] = date('Ymd', strtotime('-30 days'));
			$params['date <='] = date('Ymd');
			break;
		case 'daterange':
			if (empty($daterange)) {
				$daterange = array('start' => date('Ymd', strtotime('-30 days')), 'end' => date('Ymd'));
			}
			$params['date >='] = date('Ymd', strtotime($daterange['start']));
			$params['date <='] = date('Ymd', strtotime($daterange['end']));
			break;
	}
	$result = pdo_getall('stat_fans', $params);
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
			if ('bytotal' == $divide_type) {
				$data_y[$key] = $val['cumulate'];
			} elseif ('bycancel' == $divide_type) {
				$data_y[$key] = $val['cancel'];
			} elseif ('bynew' == $divide_type) {
				$data_y[$key] = $val['new'];
			}
		}
		if (empty($data_y[$key])) {
			$data_y[$key] = 0;
		}
	}
	iajax(0, array('data_x' => $data_x, 'data_y' => $data_y));
}