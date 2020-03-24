<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
set_time_limit(0);
load()->model('cloud');
load()->func('communication');
load()->model('extension');
$r = cloud_prepare();
if (is_error($r)) {
	itoast($r['message'], url('cloud/profile'), 'error');
}

$do = !empty($_GPC['do']) && in_array($do, array('module', 'system')) ? $_GPC['do'] : exit('Access Denied');
if ('system' == $do) {
	$lock = cache_load(cache_system_key('checkupgrade'));
	if (empty($lock) || (TIMESTAMP - 3600 > $lock['lastupdate'])) {
		$upgrade = cloud_build();
		if (!is_error($upgrade) && !empty($upgrade['upgrade'])) {
			$upgrade = array('version' => $upgrade['version'], 'release' => $upgrade['release'], 'upgrade' => 1, 'lastupdate' => TIMESTAMP);
			cache_write(cache_system_key('checkupgrade'), $upgrade);
			cache_delete(cache_system_key('cloud_transtoken'));
			iajax(0, $upgrade);
		} else {
			$upgrade = array('lastupdate' => TIMESTAMP);
			cache_delete(cache_system_key('cloud_transtoken'));
			cache_write(cache_system_key('checkupgrade'), $upgrade);
		}
	} else {
		iajax(0, $lock);
	}
}