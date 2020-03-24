<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('miniapp');

$dos = array('version_display');
$do = in_array($do, $dos) ? $do : 'version_display';

if ($do == 'version_display') {
	$version_list = miniapp_version_all($_W['uniacid']);
	template('miniapp/version-display');
}