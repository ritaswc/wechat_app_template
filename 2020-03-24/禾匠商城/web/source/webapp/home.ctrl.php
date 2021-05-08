<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$do = safe_gpc_belong($do, array('switch', 'display'), 'display');

if ('display' == $do) {
	$modulelist = uni_modules();
	if (!empty($modulelist)) {
		foreach ($modulelist as $name => &$row) {
			if (!empty($row['issystem']) || (!empty($_GPC['keyword']) && !strexists($row['title'], $_GPC['keyword'])) || (!empty($_GPC['letter']) && $row['title_initial'] != $_GPC['letter'])) {
				unset($modulelist[$name]);
				continue;
			}
		}
		$modules = $modulelist;
	}
		cache_build_account_modules($_W['uniacid']);
	template('webapp/home');
}
