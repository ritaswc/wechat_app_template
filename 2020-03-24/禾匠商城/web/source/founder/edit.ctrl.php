 <?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('user');
load()->func('file');

$dos = array('edit_base', 'edit_modules_tpl', 'edit_account');
$do = in_array($do, $dos) ? $do : 'edit_base';

$uid = intval($_GPC['uid']);
$user = user_single($uid);
if (empty($user)) {
	itoast('访问错误, 未找到该操作员.', url('user/display'), 'error');
} else {
	if (1 == $user['status']) {
		itoast('访问错误，该用户未审核通过，请先审核通过再修改！', url('user/display/check_display'), 'error');
	}
	if (3 == $user['status']) {
		itoast('访问错误，该用户已被禁用，请先启用再修改！', url('user/display/recycle_display'), 'error');
	}
}
$founders = explode(',', $_W['config']['setting']['founder']);
$profile = pdo_get('users_profile', array('uid' => $uid));
if (!empty($profile)) {
	$profile['avatar'] = tomedia($profile['avatar']);
}

if ('edit_base' == $do) {
	$account_num = permission_user_account_num($uid);
	$user['last_visit'] = date('Y-m-d H:i:s', $user['lastvisit']);
	$user['joindate'] = date('Y-m-d', $user['joindate']);
	$user['endtype'] = 0 == $user['endtime'] ? 1 : 2;

	$user['end'] = user_end_time($uid);
	$user['end'] = 0 == $user['end'] ? '永久' : $user['end'];
	$user['url'] = user_invite_register_url($uid);
	$profile = user_detail_formate($profile);
	template('user/edit-base');
}

if ('edit_modules_tpl' == $do) {
	if ($_W['isajax'] && $_W['ispost']) {
		if (intval($_GPC['groupid']) == $user['groupid']) {
			iajax(2, '未做更改！');
		}
		if (!empty($_GPC['type']) && !empty($_GPC['groupid'])) {
			$data['uid'] = $uid;
			$data[$_GPC['type']] = intval($_GPC['groupid']);
			if (user_update($data)) {
				$group_info = user_founder_group_detail_info($_GPC['groupid']);
				iajax(0, $group_info, '');
			} else {
				iajax(1, '更改失败！', '');
			}
		} else {
			iajax(-1, '参数错误！', '');
		}
	}

	$modules = user_modules($_W['uid']);
	$templates = pdo_getall('site_templates', array(), array('id', 'name', 'title'));

	$groups = user_founder_group();
	$group_info = user_founder_group_detail_info($user['groupid']);

	$extend = array();
	$users_extra_template_table = table('users_extra_templates');
	$user_extend_templates_ids = array_keys($users_extra_template_table->getExtraTemplatesIdsByUid($_GPC['uid']));
	if (!empty($user_extend_templates_ids)) {
		$extend['templates'] = pdo_getall('site_templates', array('id' => $user_extend_templates_ids), array('id', 'name', 'title'));
	}

	if (!empty($templates) && !empty($user_extend_templates_ids)) {
		foreach ($templates as $template_key => $template_val) {
			if (in_array($template_val['id'], $user_extend_templates_ids)) {
				$templates[$template_key]['checked'] = 1;
			}
		}
	}

		$uni_groups = uni_groups();
	$users_extra_group_table = table('users_extra_group');
	$user_extra_groups = $users_extra_group_table->getUniGroupsByUid($uid);
	$user_extra_groups = !empty($user_extra_groups) ? uni_groups(array_keys($user_extra_groups)) : array();
	if (!empty($uni_groups)) {
		foreach ($uni_groups as $module_group_key => &$module_group_val) {
			if (!empty($user_extra_groups[$module_group_key])) {
				$module_group_val['checked'] = 1;
			} else {
				$module_group_val['checked'] = 0;
			}
			unset($module_group_val);
		}
	}

		$users_extra_modules_table = table('users_extra_modules');
	$user_extend_modules = $users_extra_modules_table->where('uid', $uid)->getall('id');
	$extra_module_types = array();
	if (!empty($user_extend_modules)) {
		foreach ($user_extend_modules as $extend_module_info) {
			$module_info = module_fetch($extend_module_info['module_name']);
			$module_info['support'] = $extend_module_info['support'];
			if (!empty($module_info)) {
				$extend['modules'][] = $module_info;
				$extra_module_types[] = $extend_module_info['module_name'] . $module_info['support'];
			}
		}
	}

	$module_support_type = module_support_type();
	if (!empty($modules)) {
		foreach ($modules as $item) {
			if (0 != $item['issystem']) {
				continue;
			}
			foreach ($module_support_type as $module_support_type_key => $module_support_type_val) {
				if ($item[$module_support_type_key] == $module_support_type_val['support']) {
					$item['support'] = $module_support_type_key;
					$item['checked'] = 0;
					$user_modules['modules'][] = $item;
				}
			}
		}
	}

	foreach ($user_modules['modules'] as &$user_module_info) {
		if (in_array($user_module_info['name'] . $user_module_info['support'], $extra_module_types)) {
			$user_module_info['checked'] = 1;
		}
		unset($user_module_info);
	}
	template('user/edit-modules-tpl');
}

if ('edit_account' == $do) {
	$account_detail = user_account_detail_info($uid);
	$account_list = array();
	if (!empty($account_detail)) {
		foreach ($account_detail as $account_type => $accounts) {
			if (empty($accounts) || !is_array($accounts)) {
				continue;
			}
			foreach ($accounts as $uniacid => $account) {
				$account['type_name'] = $account_type;
				$account_list[$uniacid] = $account;
			}
		}
	}
	template('user/edit-account');
}