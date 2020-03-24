<?php

defined('IN_IA') or exit('Access Denied');

load()->model('phoneapp');
$account_info = permission_user_account_num();

$dos = array('create_display', 'save');
$do = in_array($do, $dos) ? $do : 'create_display';

$uniacid = intval($_GPC['uniacid']);

if (!empty($uniacid)) {
	$state = permission_account_user_role($_W['uid'], $uniacid);
	
		$role_permission = in_array($state, array(ACCOUNT_MANAGE_NAME_OWNER, ACCOUNT_MANAGE_NAME_FOUNDER, ACCOUNT_MANAGE_NAME_MANAGER, ACCOUNT_MANAGE_NAME_VICE_FOUNDER));
	
	
	if (!$role_permission) {
		itoast('无权限操作！', referer(), 'error');
	}
}

if ('save' == $do) {
	$version_id = intval($_GPC['version_id']);
	if (empty($uniacid) && empty($account_info['phoneapp_limit']) && !user_is_founder($_W['uid'])) {
		iajax(-1, '创建APP个数已满');
	}
	if (empty($_GPC['name']) && empty($_GPC['uniacid'])) {
		iajax(1, '请填写APP名称');
	}
	if (!preg_match('/^[0-9]{1,2}\.[0-9]{1,2}(\.[0-9]{1,2})?$/', trim($_GPC['version']))) {
		iajax('-1', '版本号错误，只能是数字、点，数字最多2位，例如 1.1.1 或1.2');
	}
	$modulename = safe_gpc_string(trim($_GPC['module'][0]['name']));
	$version = safe_gpc_string(trim($_GPC['module'][0]['version']));

	$version_data = array(
		'uniacid' => $uniacid,
		'description' => safe_gpc_string($_GPC['description']),
		'version' => safe_gpc_string($_GPC['version']),
		'modules' => iserializer(array($modulename => array('name' => $modulename, 'version' => $version))),
	);

	if (empty($uniacid) && empty($version_id)) {
			} elseif (!empty($version_id)) {
		$version_exist = phoneapp_version($version_id);
		if (empty($version_exist)) {
			iajax(1, '版本不存在或已删除！');
		}
		$result = pdo_update('phoneapp_versions', $version_data, array('id' => $version_id));
		if (!empty($result)) {
			table('uni_link_uniacid')->searchWithUniacidModulenameVersionid($uniacid, $modulename, $version_id)->delete();
		}
	} else {
		$result = pdo_insert('phoneapp_versions', $version_data);
		$version_id = pdo_insertid();
	}

	if (!empty($result)) {
		cache_delete(cache_system_key('user_accounts', array('type' => 'phoneapp', 'uid' => $_W['uid'])));
		iajax(0, '创建成功', url('account/display/switch', array('uniacid' => $uniacid, 'version_id' => $version_id)));
	}
	iajax(-1, '创建失败', url('phoneapp/manage/create_display'));
}

if ('create_display' == $do) {
	$version_id = intval($_GPC['version_id']);
	$version_info = phoneapp_version($version_id);
	$modules = phoneapp_support_modules();
	template('phoneapp/create');
}