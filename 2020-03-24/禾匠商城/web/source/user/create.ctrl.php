<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('user');

$dos = array('post', 'save', 'get_user_group_detail_info', 'check_vice_founder_exists', 'check_user_info', 'check_vice_founder_permission_limit');
$do = in_array($do, $dos) ? $do : 'post';

$groups = user_group();
$modules = user_modules($_W['uid']);

$modules = array_filter($modules, function ($module) {
	return empty($module['issystem']);
});
$templates = pdo_fetchall('SELECT * FROM ' . tablename('site_templates'));

$user_extra_modules = table('users_extra_modules')->getExtraModulesByUid($uid);

$module_support_type = module_support_type();
$user_modules = array('modules' => array(), 'templates' => array());
if (!empty($modules)) {
	foreach ($modules as $item) {
		if (0 == $item['issystem']) {
			foreach ($module_support_type as $module_support_type_key => $module_support_type_val) {
				if ($item[$module_support_type_key] == $module_support_type_val['support']) {
					$item['support'] = $module_support_type_key;
					$item['checked'] = 0;
					$user_modules['modules'][] = $item;
				}
			}
		}
	}
}

$uni_group_table = table('uni_group');
if (user_is_vice_founder($_W['uid'])) {
	$founder_group_info = user_founder_group_detail_info($_W['user']['groupid']);
	$modules_group_list = $founder_group_info['package_detail'];

	$uni_group_table->searchWithFounderUid($_W['uid']);
	$packages = $uni_group_table->getUniGroupList();
	$packages = uni_groups(array_column($packages, 'id'));
	$modules_group_list = array_merge($modules_group_list, $packages);
} else {
	$uni_group_table->searchWithUniacidAndUid();
	$modules_group_list = $uni_group_table->getUniGroupList();
}

if (!empty($modules_group_list)) {
	foreach ($modules_group_list as $key => $value) {
		$modules = (array) iunserializer($value['modules']);
				$modules_all = array();
		if (!empty($modules)) {
			foreach ($modules as $type => $modulenames) {
				if (empty($modulenames) || !is_array($modulenames)) {
					continue;
				}
				foreach ($modulenames as $name) {
					$modules_all[] = $name;
				}
			}
		}
		$modules_all = array_unique($modules_all);

		$module_support = array();
		foreach ($module_support_type as $support => $info) {
			if (MODULE_SUPPORT_SYSTEMWELCOME_NAME == $support) {
				continue;
			}
			if (MODULE_SUPPORT_ACCOUNT_NAME == $support) {
				$info['type'] = 'modules';
			}
			if (empty($modules[$info['type']])) {
				continue;
			}
			foreach ($modules[$info['type']] as $modulename) {
				$module_support[$modulename][$support] = $info['support'];
			}
		}

		foreach ($modules_all as $name) {
			$module = module_fetch($name);
			if (empty($module)) {
				continue;
			}

			$module['group_support'] = $module_support[$name];
			$modules_group_list[$key]['modules_all'][] = $module;
		}

		$templates = (array) iunserializer($value['templates']);
		$modules_group_list[$key]['template_num'] = !empty($templates) ? count($templates) : 0;
		$modules_group_list[$key]['templates'] = pdo_getall('site_templates', array('id' => $templates), array('id', 'name', 'title'));
	}
}

$uni_account_types = uni_account_type();
$uni_account_type_signs = array_keys(uni_account_type_sign());
foreach ($uni_account_type_signs as $type_sign_name) {
	$max_account_type_signs['max' . $type_sign_name] = 0;
}

if (user_is_vice_founder()) {
	$users_founder_own_create_groups_table = table('users_founder_own_create_groups');
	$account_group_lists = $users_founder_own_create_groups_table->getallGroupsByFounderUid($_W['uid']);
} else {
	$account_group_table = table('users_create_group');
	$account_group_lists = $account_group_table->getCreateGroupList();
}

$user_extra_limits = table('users_extra_limit')->getExtraLimitByUid($uid);
$create_account = array(
	'create_groups' => $account_group_lists,
	'create_numbers' => !empty($user_extra_limits) ? $user_extra_limits : $max_account_type_signs,
);

$source_templates = pdo_getall('site_templates', array(), array('id', 'name', 'title'));
if (!empty($source_templates)) {
	foreach ($source_templates as &$source_template) {
		$source_template['checked'] = 0;
	}
}

if ('post' == $do) {
	template('user/post');
}

if ('save' == $do) {
	$user_info = array(
		'username' => safe_gpc_string($_GPC['username']),
		'password' => $_GPC['password'],
		'repassword' => $_GPC['repassword'],
		'remark' => safe_gpc_string($_GPC['remark']),
		'starttime' => TIMESTAMP,
	);

	if (user_is_vice_founder()) {
		$user_info['owner_uid'] = $_W['uid'];
	}

	$user_add = user_info_save($user_info);
	if (is_error($user_add)) {
		iajax(-1, $user_add['message'], url('user/display'));
	}
	$uid = $user_add['uid'];

	if (!empty($_GPC['vice_founder_name'])) {
		$vice_founder_name = safe_gpc_string($_GPC['vice_founder_name']);
		$vice_founder_info = user_single(array('username' => $vice_founder_name));
		if (empty($vice_founder_info)) {
			iajax(-1, '副创始人不存在！', 'user/display');
		}
		if (!user_is_vice_founder($vice_founder_info['uid'])) {
			iajax(-1, '请勿添加非副创始人姓名！', 'user/display');
		}
	}
	if (!empty($vice_founder_name) && !empty($vice_founder_info)) {
		$vice_founder_uid = $vice_founder_info['uid'];
	} else {
		$vice_founder_uid = !user_is_vice_founder($_W['uid']) ? 0 : $_W['uid'];
	}

	if (!empty($vice_founder_uid)) {
		$founder_own_user_table = table('users_founder_own_users');
		$founder_own_add_res = $founder_own_user_table->addOwnUser($uid, $vice_founder_uid);
		if (!$founder_own_add_res) {
			iajax('-1', '添加副创始人失败', 'user/display');
		}
	}

	$user_update['groupid'] = intval($_GPC['groupid']) ? intval($_GPC['groupid']) : 0;
	$user_update['uid'] = $uid;
	if (0 == $user_update['groupid']) {
		$user_update['endtime'] = empty($_GPC['timelimit']) ? USER_ENDTIME_GROUP_DELETE_TYPE : strtotime(intval($_GPC['timelimit']) . ' days', TIMESTAMP);
	}

	user_update($user_update);

	if (!empty($_GPC['uni_groups'])) {
		$ext_group_table = table('users_extra_group');
		foreach ($_GPC['uni_groups'] as $uni_group_key => $uni_group_val) {
			$uni_group_exists = $ext_group_table->getUniGroupByUidAndGroupid($uid, $uni_group_val['id']);
			if (!$uni_group_exists) {
				$res = $ext_group_table->addExtraUniGroup($uid, $uni_group_val['id']);
				if (!$res) {
					iajax('-1', '添加应用权限组失败!', 'user/display');
				}
			}
		}
	}

	if (!empty($_GPC['modules'])) {
		$extra_modules_table = table('users_extra_modules');
		foreach ($_GPC['modules'] as $module_key => $module_val) {
			$extra_modules_table->searchByUid($uid);
			$extra_modules_table->searchBySupport($module_val['support']);
			$extra_modules_table->searchByModuleName($module_val['name']);
			$extra_module_exists = $extra_modules_table->get();
			if (!$extra_module_exists) {
				$res = $extra_modules_table->addExtraModule($uid, $module_val['name'], $module_val['support']);
				if (!$res) {
					iajax('-1', '添加附加模块失败!', 'user/display');
				}
			}
		}
	}

	if (!empty($_GPC['templates'])) {
		$extra_template_table = table('users_extra_templates');
		foreach ($_GPC['templates'] as $template_key => $template_val) {
			$extra_template_exists = $extra_template_table->getExtraTemplateByUidAndTemplateid($uid, $template_val['id']);
			if (!$extra_template_exists) {
				$res = $extra_template_table->addExtraTemplate($uid, $template_val['id']);
				if (!$res) {
					iajax('-1', '添加附加模板失败!', 'user/display');
				}
			}
		}
	}

	if (!empty($_GPC['create_account_groups'])) {
		$ext_group_table = table('users_extra_group');
		foreach ($_GPC['create_account_groups'] as $create_account_group_val) {
			$create_account_group_exists = $ext_group_table->getCreateGroupByUidAndGroupid($uid, $create_account_group_val['id']);
			if (!$create_account_group_exists) {
				$res = $ext_group_table->addExtraCreateGroup($uid, $create_account_group_val['id']);
				if (!$res) {
					iajax('-1', '添加账户权限组失败!', 'user/display');
				}
			}
		}
	}

	if (!empty($_GPC['create_account_nums']) || !empty($_GPC['timelimit'])) {
		$extra_limit_table = table('users_extra_limit');
		$extra_limit_exists = $extra_limit_table->getExtraLimitByUid($uid);
		foreach ($max_account_type_signs as $type_sign_name => $type_sign_val) {
			$data[$type_sign_name] = intval($_GPC['create_account_nums'][$type_sign_name]);
		}

		if ($extra_limit_exists) {
			$data['uid'] = $uid;
		}

		$res = $extra_limit_table->saveExtraLimit($data, $uid);
		if (!$res) {
			iajax('-1', '添加附加账户数量失败!', 'user/display');
		}
	}

	if (!empty($_GPC['timelimit'])) {
		$extra_limit_table = table('users_extra_limit');
		$extra_limit_exists = $extra_limit_table->getExtraLimitByUid($uid);
		$data = array(
			'timelimit' => intval($_GPC['timelimit']),
		);

		if ($extra_limit_exists) {
			$data['uid'] = $uid;
		}
		$extra_limit_add_res = $extra_limit_table->saveExtraLimit($data, $uid);
		if (!$extra_limit_add_res) {
			iajax('-1', '添加有效时间失败', 'user/display');
		}
	}

	iajax(0, '修改成功', url('user/display'));
}

if ('get_user_group_detail_info' == $do) {
	$user_group_id = intval($_GPC['user_group_id']);
	$user_group_detail_info = user_group_detail_info($user_group_id);
	iajax(0, $user_group_detail_info);
}

if ('check_vice_founder_exists' == $do) {
	$vice_founder_name = safe_gpc_string($_GPC['vice_founder_name']);
	$vice_founder_info = user_single(array('username' => $vice_founder_name));
	if (empty($vice_founder_info)) {
		iajax(-1, '副创始人不存在！', url('user/create'));
	}
	if (!user_is_vice_founder($vice_founder_info['uid'])) {
		iajax(-1, '请勿添加非副创始人姓名！', url('user/create'));
	}
	iajax(0, '');
}

if ('check_user_info' == $do) {
	$user = $_GPC['user'];
	$user['username'] = safe_gpc_string($user['username']);
	$check_result = user_info_check($user);
	iajax($check_result['errno'], $check_result['message'], url('user/create'));
}

if ('check_vice_founder_permission_limit' == $do) {
	if (user_is_vice_founder()) {
		$timelimit['timelimit'] = $_GPC['timelimit'];
		$create_account_nums = $_GPC['create_account_nums'];

		if (USER_CREATE_PERMISSION_GROUP_TYPE == $_GPC['permissionType']) {
			iajax(0, '权限正确');
		}

		$check_result = permission_check_vice_founder_limit(array_merge($timelimit, $create_account_nums));
		if (is_error($check_result)) {
			iajax(-1, $check_result['message']);
		} else {
			iajax(0, '权限正确');
		}
	} else {
		iajax(0, '权限错误');
	}
}