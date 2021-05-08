<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

function cron_check($cronid = 0) {
	global $_W;
	$cron = pdo_get('core_cron', array('cloudid' => $cronid));
	$_W['uniacid'] = $cron['uniacid'];
	if (empty($cron)) {
		return error(-1000, '任务不存在或已删除');
	}
	if (!$cron['status']) {
		return error(-1001, '任务已关闭');
	}
	if ('sms' != $cron['filename']) {
		if (!$cron['uniacid']) {
			return error(-1002, '任务uniacid错误1');
		}
	}
	if (empty($cron['module'])) {
		return error(-1003, '任务所属模块为空');
	} else {
		if ('task' != $cron['module']) {
			$modules = array_keys(uni_modules());
			if (!in_array($cron['module'], $modules)) {
				return error(-1004, "公众号没有操作模块{$cron['module']}的权限");
			}
		}
	}
	if (empty($cron['filename'])) {
		return error(-1005, '任务脚本名称为空');
	}

	return $cron;
}

function cron_run($id) {
	global $_W;
	$cron = pdo_get('core_cron', array('uniacid' => $_W['uniacid'], 'id' => $id));
	if (empty($cron)) {
		return false;
	}
	$extra = array();
	$extra['Host'] = $_SERVER['HTTP_HOST'];
	load()->func('communication');
	$urlset = parse_url($_W['siteurl']);
	$urlset = pathinfo($urlset['path']);
	$response = ihttp_request($_W['sitescheme'] . '127.0.0.1/' . $urlset['dirname'] . '/' . url('cron/entry', array('id' => $cron['cloudid'])), array(), $extra);
	$response = json_decode($response['content'], true);
	if (is_error($response['message'])) {
		return $response['message'];
	} else {
		cron_setnexttime($cron);
		$cron_new = pdo_get('core_cron', array('uniacid' => $_W['uniacid'], 'id' => $id));
		if (empty($cron_new)) {
			return true;
		}
		if ($cron_new['status'] != $cron['status'] || $cron_new['lastruntime'] != $cron['lastruntime'] || $cron_new['nextruntime'] != $cron['nextruntime']) {
			load()->model('cloud');
			$cron_new['id'] = $cron_new['cloudid'];
			$status = cloud_cron_update($cron_new);
			if (is_error($status)) {
				return $status;
			}
		}
	}

	return true;
}

function cron_setnexttime($cron) {
	if (empty($cron)) {
		return false;
	}
	if (1 == $cron['type']) {
		pdo_update('core_cron', array('status' => 0, 'lastruntime' => TIMESTAMP, 'nextruntime' => TIMESTAMP), array('id' => $cron['id']));

		return true;
	}
	if (!empty($cron['minute'])) {
		$cron['minute'] = explode("\t", $cron['minute']);
	}
	list($yearnow, $monthnow, $daynow, $weekdaynow, $hournow, $minutenow) = explode('-', date('Y-m-d-w-H-i', TIMESTAMP));

	if ($cron['weekday'] == -1) {
		if ($cron['day'] == -1) {
			$firstday = $daynow;
			$secondday = $daynow + 1;
		} else {
			$firstday = $cron['day'];
			$secondday = $cron['day'] + date('t', TIMESTAMP);
		}
	} else {
		$firstday = $daynow + ($cron['weekday'] - $weekdaynow);
		$secondday = $firstday + 7;
	}

	if ($firstday < $daynow) {
		$firstday = $secondday;
	}

	if ($firstday == $daynow) {
		$todaytime = cron_todaynextrun($cron);
		if ($todaytime['hour'] == -1 && $todaytime['minute'] == -1) {
			$cron['day'] = $secondday;
			$nexttime = cron_todaynextrun($cron, 0, -1);
			$cron['hour'] = $nexttime['hour'];
			$cron['minute'] = $nexttime['minute'];
		} else {
			$cron['day'] = $firstday;
			$cron['hour'] = $todaytime['hour'];
			$cron['minute'] = $todaytime['minute'];
		}
	} else {
		$cron['day'] = $firstday;
		$nexttime = cron_todaynextrun($cron, 0, -1);
		$cron['hour'] = $nexttime['hour'];
		$cron['minute'] = $nexttime['minute'];
	}
	$nextrun = mktime($cron['hour'], $cron['minute'] > 0 ? $cron['minute'] : 0, 0, $monthnow, $cron['day'], $yearnow);
	$data = array('lastruntime' => TIMESTAMP, 'nextruntime' => $nextrun);
	if ($nextrun <= TIMESTAMP) {
		$data['status'] = 0;
	}
	pdo_update('core_cron', $data, array('id' => $cron['id']));

	return true;
}

function cron_todaynextrun($cron, $hour = -2, $minute = -2) {
	$hour = $hour == -2 ? date('H', TIMESTAMP) : $hour;
	$minute = $minute == -2 ? date('i', TIMESTAMP) : $minute;

	$nexttime = array();
	if ($cron['hour'] == -1 && !$cron['minute']) {
		$nexttime['hour'] = $hour + 1;
		$nexttime['minute'] = 0;
	} elseif ($cron['hour'] == -1 && '' != $cron['minute']) {
		$nexttime['hour'] = $hour;
		if (false === ($nextminute = cron_nextminute($cron['minute'], $minute))) {
			++$nexttime['hour'];
			$nextminute = $cron['minute'][0];
		}
		$nexttime['minute'] = $nextminute;
	} elseif ($cron['hour'] != -1 && !$cron['minute']) {
		if ($cron['hour'] <= $hour) {
			$nexttime['hour'] = $nexttime['minute'] = -1;
		} else {
			$nexttime['hour'] = $cron['hour'];
			$nexttime['minute'] = 0;
		}
	} elseif ($cron['hour'] != -1 && '' != $cron['minute']) {
		$nextminute = cron_nextminute($cron['minute'], $minute);
		if ($cron['hour'] < $hour || ($cron['hour'] == $hour && false === $nextminute)) {
			$nexttime['hour'] = -1;
			$nexttime['minute'] = -1;
		} else {
			$nexttime['hour'] = $cron['hour'];
			$nexttime['minute'] = $nextminute;
		}
	}

	return $nexttime;
}

function cron_nextminute($nextminutes, $minutenow) {
	foreach ($nextminutes as $nextminute) {
		if ($nextminute > $minutenow) {
			return $nextminute;
		}
	}

	return false;
}

function cron_add($data) {
	global $_W;
	load()->model('cloud');
	if (empty($data['uniacid'])) {
		$data['uniacid'] = $_W['uniacid'];
	}
	if (empty($data['name'])) {
		return error(-1, '任务名称不能为空');
	}
	if (empty($data['filename'])) {
		return error(-1, '任务脚本不能为空');
	}
	if (empty($data['module'])) {
		return error(-1, '任务所属模块不能为空');
	}
	if (empty($data['type']) || !in_array($data['type'], array(1, 2))) {
		return error(-1, '任务的类型不能为空');
	}
	if (1 == $data['type'] && $data['lastruntime'] <= TIMESTAMP) {
		return error(-1, '定时任务的执行时间不能小于当前时间');
	} else {
		$data['nextruntime'] = $data['lastruntime'];
	}
		$data['day'] = intval($data['weekday']) == -1 ? intval($data['day']) : -1;
	$data['weekday'] = intval($data['weekday']);
	$data['hour'] = intval($data['hour']);
	$data['module'] = trim($data['module']);
	$data['minute'] = str_replace('，', ',', $data['minute']);
	if (false !== strpos($data['minute'], ',')) {
		$minutenew = explode(',', $data['minute']);
		foreach ($minutenew as $key => $val) {
			$minutenew[$key] = $val = intval($val);
			if ($val < 0 || $val > 59) {
				unset($minutenew[$key]);
			}
		}
		$minutenew = array_slice(array_unique($minutenew), 0, 2);
		$minutenew = implode("\t", $minutenew);
	} else {
		$minutenew = intval($data['minute']);
		$minutenew = $minutenew >= 0 && $minutenew < 60 ? $minutenew : '';
	}
	$data['minute'] = $minutenew;
	$data['createtime'] = TIMESTAMP;
	$data = array_elements(array('uniacid', 'name', 'filename', 'module', 'type', 'status', 'day', 'weekday', 'hour', 'minute', 'status', 'lastruntime', 'nextruntime', 'createtime', 'extra'), $data);
	$status = cloud_cron_create($data);
	if (is_error($status)) {
		return $status;
	}
	$data['cloudid'] = $status['cron_id'];

	pdo_insert('core_cron', $data);

	return pdo_insertid();
}


function cron_delete($ids) {
	global $_W;
	load()->model('cloud');
	if (empty($ids) || !is_array($ids)) {
		return true;
	}
	$ids = safe_gpc_array($ids);

	$corns = pdo_getall('core_cron', array('uniacid' => $_W['uniacid'], 'id' => $ids), array(), 'cloudid');
	$cloudid = array_keys($corns);

	if (!empty($cloudid)) {
		$status = cloud_cron_remove($cloudid);
		if (is_error($status)) {
			return $status;
		}
		pdo_delete('core_cron', array('uniacid' => $_W['uniacid'], 'id' => $ids));
	}

	return true;
}