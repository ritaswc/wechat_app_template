<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->func('cron');

$id = intval($_GPC['id']);
$cron = cron_check($id);
if (is_error($cron)) {
	message($cron, '', 'ajax');
}

$_W['uniacid'] = $cron['uniacid'];
$_W['uniaccount'] = $_W['account'] = uni_fetch($_W['uniacid']);
$_W['acid'] = $_W['account']['acid'];
$_W['weid'] = $_W['uniacid'];
$_W['cron'] = $cron;

$moduleCron = WeUtility::createModuleCron($cron['module']);
if (!is_error($moduleCron)) {
	define('IN_MODULE', $cron['module']);
	$method = 'doCron' . ucfirst($cron['filename']);
	$moduleCron->$method();
	exit();
} else {
	message($moduleCron, '', 'ajax');
}
