<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

$dos = array('display');
$do = in_array($do, $dos) ? $do : 'display';

if ('display' == $do) {
	if (!empty($_W['account']['setting']['default_module'])) {
		$eid = table('modules_bindings')->where('module', $_W['account']['setting']['default_module'])->getcolumn('eid');
	}
	if (!empty($eid)) {
		$url = !empty($_W['account']['setting']['bind_domain']) ? current($_W['account']['setting']['bind_domain']) . '/' : $_W['siteroot'];
		$url .= $_W['uniacid'] . '-' . $eid . '.html';
	}
	template('webapp/rewrite');
}