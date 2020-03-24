<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');

$dos = array('entrance_link');
$do = in_array($do, $dos) ? $do : 'entrance_link';

permission_check_account_user('wxapp_entrance_link');

$wxapp_info = miniapp_fetch($_W['uniacid']);

if ('entrance_link' == $do) {
	$wxapp_modules = table('wxapp_versions')
		->where(array('id' => $version_id))
		->getcolumn('modules');
	$module_info = array();
	if (!empty($wxapp_modules)) {
		$module_info = iunserializer($wxapp_modules);
		$module_info = table('modules_bindings')
			->where(array(
				'module' => array_keys($module_info),
				'entry' => 'page'
			))
			->getall();
	}
	template('wxapp/version-entrance');
}