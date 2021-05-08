<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('user');
load()->func('file');
load()->classs('oauth2/oauth2client');
load()->model('message');
load()->model('setting');

$dos = array('base', 'post', 'bind', 'validate_mobile', 'bind_mobile', 'unbind', 'modules_tpl', 'create_account', 'account_dateline');
$do = in_array($do, $dos) ? $do : 'base';

if ('post' == $do) {
	$type = trim($_GPC['type']);
	$extra_filed_key = safe_gpc_string($_GPC['extra_field_key']);
	$extra_filed_val = safe_gpc_string($_GPC['extra_field_val']);
	if ($_W['isfounder']) {
		$uid = is_array($_GPC['uid']) ? 0 : intval($_GPC['uid']);
	} else {
		$uid = $_W['uid'];
	}
	if (empty($uid) || empty($type)) {
		iajax(-1, '参数错误，请刷新后重试！', '');
	}
	$user = user_single($uid);
	if (empty($user)) {
		iajax(-1, '用户不存在或已经被删除！', '');
	}

	if (USER_STATUS_CHECK == $user['status'] || USER_STATUS_BAN == $user['status']) {
		iajax(-1, '访问错误，该用户未审核或者已被禁用，请先修改用户状态！', '');
	}

	$users_profile_exist = table('users_profile')->getByUid($uid);
	if ('birth' == $type) {
		if ($users_profile_exist['year'] == $_GPC['year'] && $users_profile_exist['month'] == $_GPC['month'] && $users_profile_exist['day'] == $_GPC['day']) {
			iajax(0, '未作修改！', '');
		}
	} elseif ('reside' == $type) {
		if ($users_profile_exist['province'] == $_GPC['province'] && $users_profile_exist['city'] == $_GPC['city'] && $users_profile_exist['district'] == $_GPC['district']) {
			iajax(0, '未作修改！', '');
		}
	} else {
		if (in_array($type, array('username', 'password', 'welcome_link'))) {
			if ($user[$type] == $_GPC[$type] && 'password' != $type) {
				iajax(0, '未做修改！', '');
			}
		} else {
			if ($users_profile_exist[$type] == $_GPC[$type] && empty($extra_filed_key)) {
				iajax(0, '未作修改！', '');
			}
		}
	}
	switch ($type) {
		case 'avatar':
		case 'realname':
		case 'address':
		case 'qq':
		case 'mobile':
			if ('mobile' == $type) {
				$match = preg_match(REGULAR_MOBILE, trim($_GPC[$type]));
				if (empty($match)) {
					iajax(-1, '手机号不正确', '');
				}
				$users_mobile = pdo_get('users_profile', array('mobile' => trim($_GPC[$type]), 'uid <>' => $uid));
				$bind_mobile = pdo_get('users_bind', array('bind_sign' => trim($_GPC[$type]), 'uid <>' => $uid));
				if (!empty($users_mobile) || !empty($bind_mobile)) {
					iajax(-1, '手机号已存在，请联系管理员', '');
				}
			}
			if ($users_profile_exist) {
				$result = pdo_update('users_profile', array($type => trim($_GPC[$type])), array('uid' => $uid));
			} else {
				$data = array(
					'uid' => $uid,
					'createtime' => TIMESTAMP,
					$type => trim($_GPC[$type]),
				);
				$result = pdo_insert('users_profile', $data);
			}
			if ('mobile' == $type) {
				$data = array(
					'uid' => $uid,
					'bind_sign' => trim($_GPC[$type]),
					'third_nickname' => trim($_GPC[$type]),
					'third_type' => USER_REGISTER_TYPE_MOBILE,
				);
				$users_bind_exist = pdo_get('users_bind', array('uid' => $uid, 'third_type' => USER_REGISTER_TYPE_MOBILE));
				if ($users_bind_exist) {
					$result_bind = pdo_update('users_bind', $data, array('uid' => $uid, 'third_type' => USER_REGISTER_TYPE_MOBILE));
				} else {
					$result_bind = pdo_insert('users_bind', $data);
				}
				if (!$result_bind) {
					iajax(-1, '操作失败，请联系管理员解决！', '');
				}
			}
			break;
		case 'username':
			$founders = explode(',', $_W['config']['setting']['founder']);
			if (!in_array($_W['uid'], $founders) && $_W['uid'] != $user['owner_uid']) {
				iajax(-1, '无权限修改，请联系网站创始人！');
			}
			$username = safe_gpc_string($_GPC['username']);
			$name_exist = pdo_get('users', array('username' => $username));
			if (!empty($name_exist)) {
				iajax(-1, '用户名已存在，请更换其他用户名！', '');
			}
			$result = pdo_update('users', array('username' => $username), array('uid' => $uid));
			break;
		case 'vice_founder_name':
			$userinfo = user_single(array('username' => safe_gpc_string($_GPC['vice_founder_name'])));
			if (empty($userinfo) || ACCOUNT_MANAGE_GROUP_VICE_FOUNDER != $userinfo['founder_groupid']) {
				iajax(-1, '用户不存在或该用户不是副创始人', '');
			}

			$founder_own_user_table = table('users_founder_own_users');
			$founder_own_user_exists = $founder_own_user_table->getFounderByUid($uid);
			if (!empty($founder_own_user_exists)) {
				$result = $founder_own_user_table->updateOwnUser($uid, $userinfo['uid']);
			} else {
				$result = $founder_own_user_table->addOwnUser($uid, $userinfo['uid']);
			}
			break;
		case 'remark':
			$result = pdo_update('users', array('remark' => safe_gpc_string($_GPC['remark'])), array('uid' => $uid));
			break;
		case 'welcome_link':
			$welcome_link = intval($_GPC['welcome_link']);
			$result = pdo_update('users', array('welcome_link' => $welcome_link), array('uid' => $uid));
			break;
		case 'password':
			if ($_GPC['newpwd'] !== $_GPC['renewpwd']) {
				iajax(-1, '两次密码不一致！', '');
			}
			$_GPC['newpwd'] = safe_gpc_string($_GPC['newpwd']);
			$check_safe = safe_check_password($_GPC['newpwd']);
			if (is_error($check_safe)) {
				iajax(-1, $check_safe['message']);
			}

			if (!$_W['isfounder'] && empty($user['register_type'])) {
				$pwd = user_password($_GPC['oldpwd'], $uid);
				$pwd_hash = user_password_hash($pwd, $uid);
				if ($pwd_hash != $user['hash']) {
					iajax(-1, '原密码不正确！', '');
				}
			}
			$newpwd = user_password($_GPC['newpwd'], $uid);
			$newpwd_hash = user_password_hash($newpwd, $uid);
			if ($newpwd_hash == $user['hash']) {
				iajax(0, '未作修改！', '');
			}
			$result = pdo_update('users', array('password' => $newpwd), array('uid' => $uid));
			break;
		case 'endtime':
			if (1 == $_GPC['endtype']) {
				$endtime = 0;
			} else {
				$endtime = strtotime($_GPC['endtime']);
			}
			
				if (user_is_vice_founder() && !empty($_W['user']['endtime']) && ($endtime > $_W['user']['endtime'] || empty($endtime))) {
					iajax(-1, '副创始人给用户设置的时间不能超过自己的到期时间');
				}
			
			$result = pdo_update('users', array('endtime' => $endtime), array('uid' => $uid));
			pdo_update('users_profile', array('send_expire_status' => 0), array('uid' => $uid));
			$uni_account_user = pdo_getall('uni_account_users', array('uid' => $uid, 'role' => 'owner'));
			if (!empty($uni_account_user)) {
				foreach ($uni_account_user as $account) {
					cache_delete(cache_system_key('uniaccount', array('uniacid' => $account['uniacid'])));
				}
			}
			break;
		case 'birth':
			if ($users_profile_exist) {
				$result = pdo_update('users_profile', array('birthyear' => intval($_GPC['year']), 'birthmonth' => intval($_GPC['month']), 'birthday' => intval($_GPC['day'])), array('uid' => $uid));
			} else {
				$data = array(
					'uid' => $uid,
					'createtime' => TIMESTAMP,
					'birthyear' => intval($_GPC['year']),
					'birthmonth' => intval($_GPC['month']),
					'birthday' => intval($_GPC['day']),
				);
				$result = pdo_insert('users_profile', $data);
			}
			break;
		case 'reside':
			$province = safe_gpc_string($_GPC['province']);
			$city = safe_gpc_string($_GPC['city']);
			$district = safe_gpc_string($_GPC['district']);
			if ($users_profile_exist) {
				$result = pdo_update('users_profile', array('resideprovince' => $province, 'residecity' => $city, 'residedist' => $district), array('uid' => $uid));
			} else {
				$data = array(
					'uid' => $uid,
					'createtime' => TIMESTAMP,
					'resideprovince' => $province,
					'residecity' => $city,
					'residedist' => $district,
				);
				$result = pdo_insert('users_profile', $data);
			}
			break;
		default:
			$allow_fields = array(
				'realname', 'nickname', 'avatar', 'qq', 'mobile', 'gender', 'birthyear', 'birthmonth', 'birthday', 'constellation', 'zodiac', 'idcard', 'studentid', 'grade', 'address', 'zipcode', 'nationality', 'resideprovince', 'residecity', 'residedist', 'graduateschool', 'company', 'education', 'occupation', 'position', 'revenue', 'affectivestatus', 'lookingfor', 'bloodtype', 'height', 'weight', 'alipay', 'msn', 'email', 'taobao', 'site', 'bio', 'interest',
			);
			if (empty($extra_filed_key) || !in_array($extra_filed_key, $allow_fields)) {
				iajax(-1, '参数错误', '');
			}
			if ($users_profile_exist) {
				$result = pdo_update('users_profile', array($extra_filed_key => $extra_filed_val), array('uid' => $uid));
			} else {
				$data = array(
					'uid' => $uid,
					$extra_filed_key => $extra_filed_val,
				);
				$result = pdo_insert('users_profile', $data);
			}
			break;
	}
	if ($result) {
		pdo_update('users_profile', array('edittime' => TIMESTAMP), array('uid' => $uid));
		iajax(0, '修改成功！', '');
	} else {
		iajax(-1, '修改失败，请稍候重试！', '');
	}
}

if ('base' == $do) {
	$account_num = permission_user_account_num($_W['uid']);
	$message_id = intval($_GPC['message_id']);
	message_notice_read($message_id);

	$user_type = !empty($_GPC['user_type']) ? trim($_GPC['user_type']) : PERSONAL_BASE_TYPE;
		$user = user_single($_W['uid']);
	if (empty($user)) {
		itoast('抱歉，用户不存在或是已经被删除！', url('user/profile'), 'error');
	}
	$user['last_visit'] = date('Y-m-d H:i:s', $user['lastvisit']);
	$user['joindate'] = date('Y-m-d H:i:s', $user['joindate']);
	$user['url'] = user_invite_register_url($_W['uid']);
	$user_founder_info = table('users_founder_own_users')->getFounderByUid($user['uid']);

	$profile = pdo_get('users_profile', array('uid' => $_W['uid']));

	$profile = user_detail_formate($profile);

	
		if (!$_W['isfounder'] || user_is_vice_founder()) {
						if ($_W['user']['founder_groupid'] == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
				$groups = user_founder_group();
				$group_info = user_founder_group_detail_info($user['groupid']);
			} else {
				$groups = user_group();
				$group_info = user_group_detail_info($user['groupid']);
			}

						$account_detail = user_account_detail_info($_W['uid']);
		}
	
	

	if (empty($_W['isfounder'])) {
		$user_extra_groups = table('users_extra_group')->getUniGroupsByUid($_W['uid']);
		$user_extra_groups = !empty($user_extra_groups) ? uni_groups(array_keys($user_extra_groups)) : array();

		$extend = array();
		$user_extend_templates_ids = array_keys((array) table('users_extra_templates')->getExtraTemplatesIdsByUid($_W['uid']));
		if (!empty($user_extend_templates_ids)) {
			$extend['templates'] = pdo_getall('site_templates', array('id' => $user_extend_templates_ids), array('id', 'name', 'title'));
		}
		$user_extend_modules = table('users_extra_modules')->getExtraModulesByUid($_W['uid']);
		if (!empty($user_extend_modules)) {
			foreach ($user_extend_modules as $extend_module_info) {
				$module_info = module_fetch($extend_module_info['module_name']);
				if (!empty($module_info)) {
					$module_info['support'] = $extend_module_info['support'];
					$extend['modules'][] = $module_info;
				}
			}
		}
	}

	$extra_fields = user_available_extra_fields();

	$redirect_urls = array(
		array('id' => WELCOME_DISPLAY_TYPE, 'name' => '用户欢迎页'),
		array('id' => PLATFORM_DISPLAY_TYPE, 'name' => '最后进入的平台或应用'),
	);
	$support_login_urls = user_support_urls();

	$lists = table('users_bind')->getAllByUid($_W['uid']);
	$bind_qq = array();
	$bind_wechat = array();
	$bind_mobile = array();
	if (!empty($lists)) {
		foreach ($lists as $list) {
			switch ($list['third_type']) {
				case USER_REGISTER_TYPE_QQ:
					$bind_qq = $list;
					break;
				case USER_REGISTER_TYPE_WECHAT:
					$bind_wechat = $list;
					break;
				case USER_REGISTER_TYPE_MOBILE:
					$bind_mobile = $list;
					break;
			}
		}
	}

	$extra_limit_table = table('users_extra_limit');
	$extra_limit_info = $extra_limit_table->getExtraLimitByUid($_W['uid']);

	$endtime = $user['endtime'];
	$total_timelimit = $group_info['timelimit'] + $extra_limit_info['timelimit'];

	if (USER_ENDTIME_GROUP_EMPTY_TYPE == $endtime || USER_ENDTIME_GROUP_UNLIMIT_TYPE == $endtime) {
		$total_timelimit = '永久';
		$endtime = '永久';
	} elseif (USER_ENDTIME_GROUP_DELETE_TYPE == $endtime && 0 == $total_timelimit) {
		$endtime = 0 == $total_timelimit ? date('Y-m-d', $user['joindate']) : date('Y-m-d', $user['endtime']);
	} else {
		$endtime = date('Y-m-d', $endtime);
	}

	$setting_sms_sign = setting_load('site_sms_sign');
	$bind_sign = !empty($setting_sms_sign['site_sms_sign']['register']) ? $setting_sms_sign['site_sms_sign']['register'] : '';

	template('user/profile');
}

if ('bind' == $do) {
	$setting_sms_sign = setting_load('site_sms_sign');
	$bind_sign = !empty($setting_sms_sign['site_sms_sign']['register']) ? $setting_sms_sign['site_sms_sign']['register'] : '';

	$user = table('users')->getById($_W['uid']);
	$user_profile = table('users_profile')->getByUid($_W['uid']);

	$bind_info = table('users_bind')->getAllByUid($_W['uid']);

	$signs = array_keys($bind_info);

	if (!empty($user['openid']) && !in_array($user['openid'], $signs)) {
		pdo_insert('users_bind', array('uid' => $user['uid'], 'bind_sign' => $user['openid'], 'third_type' => $user['register_type'], 'third_nickname' => $user_profile['nickname']));
	}

	if (!empty($user_profile['mobile']) && !in_array($user_profile['mobile'], $signs)) {
		pdo_insert('users_bind', array('uid' => $user_profile['uid'], 'bind_sign' => $user_profile['mobile'], 'third_type' => USER_REGISTER_TYPE_MOBILE, 'third_nickname' => $user_profile['mobile']));
	}

	$lists = table('users_bind')->getAllByUid($_W['uid']);

	$bind_qq = array();
	$bind_wechat = array();
	$bind_mobile = array();

	if (!empty($lists)) {
		foreach ($lists as $list) {
			switch ($list['third_type']) {
				case USER_REGISTER_TYPE_QQ:
					$bind_qq = $list;
					break;
				case USER_REGISTER_TYPE_WECHAT:
					$bind_wechat = $list;
					break;
				case USER_REGISTER_TYPE_MOBILE:
					$bind_mobile = $list;
					break;
			}
		}
	}

	$support_login_urls = user_support_urls();

	template('user/bind');
}

if (in_array($do, array('validate_mobile', 'bind_mobile')) || USER_REGISTER_TYPE_MOBILE == $_GPC['bind_type'] && 'unbind' == $do) {
	$user_bind = table('users_bind')->getByTypeAndUid(USER_REGISTER_TYPE_MOBILE, $_W['uid']);

	$mobile = safe_gpc_string($_GPC['mobile']);
	$type = trim($_GPC['type']);

	$mobile_exists = table('users_profile')->getByMobile($mobile);
	if (empty($mobile)) {
		iajax(-1, '手机号不能为空');
	}
	if (!preg_match(REGULAR_MOBILE, $mobile)) {
		iajax(-1, '手机号格式不正确');
	}

	if (!empty($type) && $mobile != $user_bind['bind_sign']) {
		iajax(-1, '请输入已绑定的手机号');
	}

	if (empty($type) && !empty($mobile_exists)) {
		iajax(-1, '手机号已存在');
	}
}
if ('validate_mobile' == $do) {
	$user = array('username' => safe_gpc_string($_GPC['mobile']));
	$mobile_exists = user_check($user);
	if ($mobile_exists) {
		iajax(-1, '手机号已经存在');
	}
	iajax(0, '本地校验成功');
}

if ('bind_mobile' == $do) {
	if ($_W['isajax'] && $_W['ispost']) {
		$bind_info = OAuth2Client::create('mobile')->bind();

		$user = array('username' => safe_gpc_string($_GPC['mobile']));
		$mobile_exists = user_check($user);
		if ($mobile_exists) {
			iajax(-1, '手机号已经存在');
		}

		if (is_error($bind_info)) {
			iajax(-1, $bind_info['message']);
		}
		iajax(0, '绑定成功', url('user/profile/bind'));
	} else {
		iajax(-1, '非法请求');
	}
}

if ('unbind' == $do) {
	$types = array(1 => 'qq', 2 => 'wechat', 3 => 'mobile');
	if (!in_array($_GPC['bind_type'], array(USER_REGISTER_TYPE_QQ, USER_REGISTER_TYPE_WECHAT, USER_REGISTER_TYPE_MOBILE))) {
		iajax(-1, '类型错误');
	}
	$bind_type = $types[$_GPC['bind_type']];
	if ($_W['isajax'] && $_W['ispost']) {
		$unbind_info = OAuth2Client::create($bind_type, $_W['setting']['thirdlogin'][$bind_type]['appid'], $_W['setting']['thirdlogin'][$bind_type]['appsecret'])->unbind();

		if (is_error($unbind_info)) {
			iajax(-1, $unbind_info['message']);
		}
		$settings = $_W['setting']['copyright'];
		if (user_is_founder($_W['uid'], true) && $settings['login_verify_status']) {
			$settings['login_verify_status'] = 0;
			setting_save($settings, 'copyright');
		}
		iajax(0, '解绑成功', url('user/profile/bind'));
	}
	iajax(-1, '非法请求');
}

if ('modules_tpl' == $do) {
	$modules = user_modules($_W['uid']);
	$templates = pdo_getall('site_templates', array(), array('id', 'name', 'title'));

	$group_info = user_group_detail_info($_W['user']['groupid']);

	$extend = array();
	$users_extra_template_table = table('users_extra_templates');
	$user_extend_templates_ids = array_keys($users_extra_template_table->getExtraTemplatesIdsByUid($_W['uid']));
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

	$users_extra_group_table = table('users_extra_group');
	$extra_groups = $users_extra_group_table->getUniGroupsByUid($_W['uid']);
	if (!empty($extra_groups)) {
		$uni_groups = uni_groups(array_keys($extra_groups));
	} else {
		$uni_groups = array();
	}

	$users_extra_modules_table = table('users_extra_modules');
	$user_extend_modules = array_keys($users_extra_modules_table->getExtraModulesByUid($_W['uid']));
	if (!empty($user_extend_modules)) {
		foreach ($user_extend_modules as $module_name) {
			$module_info = module_fetch($module_name);
			if (!empty($module_info)) {
				$extend['modules'][$module_name] = $module_info;
			}
		}
	}

	$user_modules = array('account' => array(), 'wxapp' => array(), 'webapp' => array(), 'phoneapp' => array(), 'xzapp' => array());
	if (!empty($modules)) {
		foreach ($modules as $module) {
			if (0 == $module['issystem']) {
				if (MODULE_SUPPORT_ACCOUNT == $module[MODULE_SUPPORT_ACCOUNT_NAME]) {
					if (!empty($user_extend_modules) && in_array($module['name'], $user_extend_modules)) {
						$module['checked'] = 1;
					}
					$user_modules['account'][] = $module;
					$module['checked'] = 0;
				}

				if (MODULE_SUPPORT_WXAPP == $module[MODULE_SUPPORT_WXAPP_NAME]) {
					if (!empty($user_extend_modules) && in_array($module['name'], $user_extend_modules)) {
						$module['checked'] = 1;
					}
					$user_modules['wxapp'][] = $module;
					$module['checked'] = 0;
				}

				if (MODULE_SUPPORT_WEBAPP == $module[MODULE_SUPPORT_WEBAPP_NAME]) {
					if (!empty($user_extend_modules) && in_array($module['name'], $user_extend_modules)) {
						$module['checked'] = 1;
					}
					$user_modules['webapp'][] = $module;
					$module['checked'] = 0;
				}

				if (MODULE_SUPPORT_PHONEAPP == $module[MODULE_SUPPORT_PHONEAPP_NAME]) {
					if (!empty($user_extend_modules) && in_array($module['name'], $user_extend_modules)) {
						$module['checked'] = 1;
					}
					$user_modules['phoneapp'][] = $module;
					$module['checked'] = 0;
				}

				if (MODULE_SUPPORT_XZAPP == $module[MODULE_SUPPORT_XZAPP_NAME]) {
					if (!empty($user_extend_modules) && in_array($module['name'], $user_extend_modules)) {
						$module['checked'] = 1;
					}
					$user_modules['xzapp'][] = $module;
					$module['checked'] = 0;
				}

				if (MODULE_SUPPORT_ALIAPP == $module[MODULE_SUPPORT_ALIAPP_NAME]) {
					if (!empty($user_extend_modules) && in_array($module['name'], $user_extend_modules)) {
						$module['checked'] = 1;
					}
					$user_modules['aliapp'][] = $module;
					$module['checked'] = 0;
				}
			}
		}
	}

	template('user/profile-modules-tpl');
}

if ('create_account' == $do) {
	$user_permission_account = permission_user_account_num($_W['uid']);

	template('user/profile-create-account-list');
}

if ('account_dateline' == $do) {
	$group_info = table('users_group')->getById($_W['user']['groupid']);
	$extra_limit_table = table('users_extra_limit');
	$extra_limit_info = $extra_limit_table->getExtraLimitByUid($_W['uid']);

	$total_timelimit = $group_info['timelimit'] + $extra_limit_info['timelimit'];
	$starttime = date('Y-m-d', $_W['user']['starttime']);
	$endtime = date('Y-m-d', strtotime('+' . $total_timelimit . ' day', strtotime($starttime)));

	template('user/profile-account-dateline');
}