<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');

$dos = array('post', 'get_wxapp_modules', 'module_binding');
$do = in_array($do, $dos) ? $do : 'post';

$type = intval($_GPC['type']);
if (!in_array($type, array_keys($account_all_type))) {
	itoast('账号类型不存在,请重试.');
}
$account_info = permission_user_account_num($_W['uid']);
$type_sign = $account_all_type[$type]['type_sign'];

if ('post' == $do) {
	$uniacid = intval($_GPC['uniacid']);
	$miniapp_info = miniapp_fetch($uniacid);
	if (empty($miniapp_info)) {
		itoast('参数有误');
	}

	if (checksubmit('submit')) {
		if (!preg_match('/^[0-9]{1,2}\.[0-9]{1,2}(\.[0-9]{1,2})?$/', trim($_GPC['version']))) {
			iajax(-1, '版本号错误，只能是数字、点，数字最多2位，例如 1.1.1 或1.2');
		}

		$version = array(
			'uniacid' => $uniacid,
			'multiid' => '0',
			'description' => safe_gpc_string($_GPC['description']),
			'version' => safe_gpc_string($_GPC['version']),
			'modules' => '',
			'createtime' => TIMESTAMP,
		);
		if (WXAPP_TYPE_SIGN == $type_sign) {
			$version['design_method'] = WXAPP_MODULE;
		}
		$module = module_fetch($_GPC['choose_module']['name']);
		if (!empty($module)) {
			$select_modules[$module['name']] = array(
				'name' => $module['name'],
				'version' => $module['version'],
			);
			$version['modules'] = serialize($select_modules);
		}

		table('wxapp_versions')->fill($version)->save();
		$version_id = pdo_insertid();
		if (empty($version_id)) {
			iajax(-1, '创建失败');
		} else {
			cache_delete(cache_system_key('user_accounts', array('type' => $account_all_type[$type]['type_sign'], 'uid' => $_W['uid'])));
			iajax(0, '创建成功', url('account/display/switch', array('uniacid' => $uniacid, 'version_id' => $version_id, 'type' => $type)));
		}
	}
	template('miniapp/post');
}
