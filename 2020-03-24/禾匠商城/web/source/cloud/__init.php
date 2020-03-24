<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

if (in_array($action, array('sms', 'sms-sign'))) {
	define('FRAME', 'system');
}
if ('process' == $action) {
	define('FRAME', '');
} else {
	define('FRAME', 'site');
}

if(in_array($action, array('profile', 'device', 'callback', 'appstore'))) {
	$do = $action;
	$action = 'redirect';
}

if ('touch' == $action) {
	exit('success');
}