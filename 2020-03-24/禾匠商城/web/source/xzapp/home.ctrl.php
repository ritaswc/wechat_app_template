<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */


load()->model('xzapp');
load()->model('account');

$do = safe_gpc_belong($do, array('display'), 'display');

if ($do == 'display') {
	$last_uniacid = uni_account_last_switch();

	if (empty($last_uniacid)) {
		itoast('', url('account/display', array('type' => XZAPP_TYPE_SIGN)), 'info');
	}

	if (!empty($last_uniacid) && $last_uniacid != $_W['uniacid']) {
		uni_account_switch($last_uniacid, url('xzapp/home'), XZAPP_TYPE_SIGN);
	}

	$account = uni_fetch($last_uniacid);
	$modulelist = uni_modules(false);
	if (!empty($modulelist)) {
		foreach ($modulelist as $name => &$row) {
			if (!empty($row['issystem']) || $row['xzapp_support'] != 2 || (!empty($_GPC['keyword']) && !strexists($row['title'], $_GPC['keyword'])) || (!empty($_GPC['letter']) && $row['title_initial'] != $_GPC['letter'])) {
				unset($modulelist[$name]);
				continue;
			}
		}
		$modules = $modulelist;
	}
	template('xzapp/home');
}
