<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
if (in_array($action, array('site', 'menu', 'attachment', 'systeminfo', 'logs', 'filecheck', 'optimize',
	'database', 'scan', 'bom', 'ipwhitelist', 'sensitiveword', 'thirdlogin', 'oauth', 'usersetting', 'job', 'check', 'save_setting', 'scrapfile', ))) {
	define('FRAME', 'site');
}
if ('platform' == $action) {
	define('FRAME', 'account_manage');
}
if ('workorder' == $action) {
	define('FRAME', 'workorder');
}
if (in_array($action, array('template', 'updatecache'))) {
	define('FRAME', 'system');
}