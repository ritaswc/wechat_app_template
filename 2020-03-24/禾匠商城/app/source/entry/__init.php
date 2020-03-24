<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');

if (empty($action)) {
	$action = 'site';
}

$eid = intval($_GPC['eid']);
if(!empty($eid)) {
	$entry = module_entry($eid);
} else {
	$entry = array(
		'module' => $_GPC['m'],
		'do' => $_GPC['do'],
		'state' => $_GPC['state'],
		'direct' => 0,
	);
}
$module_exist_in_account = table('uni_modules')->where(array('uniacid' => $_W['uniacid'], 'module_name' => $entry['module']))->get();
if (empty($module_exist_in_account) && !in_array($entry['module'], module_system())) {
	message('您访问的功能模块不存在，请重新进入');
}
if(empty($entry) || empty($entry['do'])) {
	message('非法访问.');
}

$_GPC['__entry'] = $entry['title'];
$_GPC['__state'] = $entry['state'];
$_GPC['state'] = $entry['state'];
$_GPC['m'] = $entry['module'];
$_GPC['do'] = $entry['do'];

$_W['current_module'] = module_fetch($entry['module']);
define('IN_MODULE', $entry['module']);