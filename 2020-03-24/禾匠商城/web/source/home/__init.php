<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

if ('system_home' == $do) {
	define('FRAME', 'welcome');
}

if ('system' == $do) {
	if ('home' == safe_gpc_string($_GPC['page'])) {
		define('FRAME', 'welcome');
	} else {
		define('FRAME', 'system');
	}
}

if (in_array($do, array('platform', 'ext', 'account_ext')) || empty($do)) {
	define('FRAME', 'account');
}