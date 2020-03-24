<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('module');
load()->model('miniapp');

function current_operate_is_controller() {
	global $_W, $_GPC;
	$result = 0;
	if (!$_W['isfounder']) {
		return $result;
	}
	$result = igetcookie('__iscontroller');
	if (isset($_GPC['iscontroller'])) {
		if (1 == $_GPC['iscontroller']) {
			$result = 1;
			isetcookie('__iscontroller', $result);
			return $result;
		}
		if (0 == $_GPC['iscontroller']) {
			$result = 0;
		}
	}

	if (in_array(FRAME, array('welcome', 'module_manage', 'user_manage', 'permission', 'system', 'site'))) {
		$result = 1;
	}
	if (in_array(FRAME, array('account', 'wxapp')) && $_GPC['m'] != 'store') {
		$result = 0;
	}
	isetcookie('__iscontroller', $result);
	return $result;
}

function system_modules() {
	return module_system();
}


function url($segment, $params = array(), $contain_domain = false) {
	return wurl($segment, $params, $contain_domain);
}


function message($msg, $redirect = '', $type = '', $tips = false, $extend = array()) {
	global $_W, $_GPC;

	if ('refresh' == $redirect) {
		$redirect = $_W['script_name'] . '?' . $_SERVER['QUERY_STRING'];
	}
	if ('referer' == $redirect) {
		$redirect = referer();
	}
		$redirect = safe_gpc_url($redirect);

	if ('' == $redirect) {
		$type = in_array($type, array('success', 'error', 'info', 'warning', 'ajax', 'sql', 'expired')) ? $type : 'info';
	} else {
		$type = in_array($type, array('success', 'error', 'info', 'warning', 'ajax', 'sql', 'expired')) ? $type : 'success';
	}
	if ($_W['isajax'] || !empty($_GET['isajax']) || 'ajax' == $type) {
		if ('ajax' != $type && !empty($_GPC['target'])) {
			exit('
<script type="text/javascript">
	var url = ' . (!empty($redirect) ? 'parent.location.href' : "''") . ";
	var modalobj = util.message('" . $msg . "', '', '" . $type . "');
	if (url) {
		modalobj.on('hide.bs.modal', function(){\$('.modal').each(function(){if(\$(this).attr('id') != 'modal-message') {\$(this).modal('hide');}});top.location.reload()});
	}
</script>");
		} else {
			$vars = array();
			$vars['message'] = $msg;
			$vars['redirect'] = $redirect;
			$vars['type'] = $type;
			exit(json_encode($vars));
		}
	}
	if (empty($msg) && !empty($redirect)) {
		header('Location: ' . $redirect);
		exit;
	}
	$label = $type;
	if ('error' == $type || 'expired' == $type) {
		$label = 'danger';
	}
	if ('ajax' == $type || 'sql' == $type) {
		$label = 'warning';
	}

	if ($tips) {
		if (is_array($msg)) {
			$message_cookie['title'] = 'MYSQL 错误';
			$message_cookie['msg'] = 'php echo cutstr(' . $msg['sql'] . ', 300, 1);';
		} else {
			$message_cookie['title'] = $caption;
			$message_cookie['msg'] = $msg;
		}
		$message_cookie['type'] = $label;
		$message_cookie['redirect'] = $redirect ? $redirect : referer();
		$message_cookie['msg'] = rawurlencode($message_cookie['msg']);
		$extend_button = array();
		if (!empty($extend) && is_array($extend)) {
			foreach ($extend as $button) {
				if (!empty($button['title']) && !empty($button['url'])) {
					$button['url'] = safe_gpc_url($button['url'], false);
					$button['title'] = rawurlencode($button['title']);
					$extend_button[] = $button;
				}
			}
		}
		$message_cookie['extend'] = !empty($extend_button) ? $extend_button : '';

		isetcookie('message', stripslashes(json_encode($message_cookie, JSON_UNESCAPED_UNICODE)));
		header('Location: ' . $message_cookie['redirect']);
	} else {
		include template('common/message', TEMPLATE_INCLUDEPATH);
	}
	exit;
}

function iajax($code = 0, $message = '', $redirect = '') {
	message(error($code, $message), $redirect, 'ajax', false);
}

function itoast($message, $redirect = '', $type = '', $extend = array()) {
	message($message, $redirect, $type, true, $extend);
}


function checklogin() {
	global $_W;
	if (empty($_W['uid'])) {
		if (!empty($_W['setting']['copyright']['showhomepage'])) {
			itoast('', url('account/welcome'), 'warning');
		} else {
			itoast('', url('user/login'), 'warning');
		}
	}
	$cookie = json_decode(authcode(igetcookie('__session'), 'DECODE'), true);
	if (empty($cookie['rember'])) {
		$session = authcode(json_encode($cookie), 'encode');
		$autosignout = (int)$_W['setting']['copyright']['autosignout'] > 0 ? (int)$_W['setting']['copyright']['autosignout'] * 60 : 0;
		isetcookie('__session', $session, $autosignout, true);
	}

	return true;
}

function get_position_by_ip($ip = '') {
	$ip = $ip ? $ip : CLIENT_IP;
	$url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
	$ip_content = file_get_contents($url);
	$ip_content = json_decode($ip_content, true);
	if (empty($ip_content) || $ip_content['code'] != 0) {
		$res = @file_get_contents('https://whois.pconline.com.cn/ipJson.jsp');
		$res = strtoutf8($res);
		$json_matches = array();
		preg_match('/{IPCallBack\((.+?)\);\}/', $res, $json_matches);
		if (empty($json_matches[1])) {
			return error(-1, '获取地址失败，请重新配置Ip查询接口');
		}
		$ip_content = array(
			'code' => 0,
			'data' => json_decode($json_matches[1], true)
		);
	}
	return $ip_content;
}

function buildframes($framename = '') {
	global $_W, $_GPC, $top_nav;
	load()->model('system');

	if (!empty($GLOBALS['frames']) && !empty($_GPC['m'])) {
		$frames = array();
		$globals_frames = (array) $GLOBALS['frames'];
		foreach ($globals_frames as $key => $row) {
			if (empty($row)) {
				continue;
			}
			$row = (array) $row;
			$frames['section']['platform_module_menu' . $key]['title'] = $row['title'];
			if (!empty($row['items'])) {
				foreach ($row['items'] as $li) {
					$frames['section']['platform_module_menu' . $key]['menu']['platform_module_menu' . $li['id']] = array(
						'title' => "<i class='wi wi-appsetting'></i> {$li['title']}",
						'url' => $li['url'],
						'is_display' => 1,
					);
				}
			}
		}

		return $frames;
	}

	$frames = system_menu_permission_list();
			if (!empty($_W['role']) && (empty($_W['isfounder']) || user_is_vice_founder())) {
		$account_info = uni_fetch($_W['uniacid']);
		$type_sign = 'account' == $account_info->typeSign ? 'system' : $account_info->typeSign;
		$user_permission = permission_account_user($type_sign);
	}
	if (empty($_W['role']) && empty($_W['uniacid'])) {
		$user_permission = permission_account_user('system');
	}
		if (!empty($user_permission)) {
		foreach ($frames as $nav_id => $section) {
			if (empty($section['section'])) {
				continue;
			}
			foreach ($section['section'] as $section_id => $secion) {
				if ('account' == $nav_id) {
					if ($status && !empty($module_permission) && in_array('account*', $user_permission) && 'platform_module' != $section_id && ACCOUNT_MANAGE_NAME_OWNER != permission_account_user_role($_W['uid'], $_W['uniacid'])) {
						$frames['account']['section'][$section_id]['is_display'] = false;
						continue;
					} else {
						if (in_array('account*', $user_permission)) {
							continue;
						}
					}
				}
				
				
					if ('wxapp' != $nav_id && 'store' != $nav_id) {
						$section_show = false;
						$secion['if_fold'] = !empty($_GPC['menu_fold_tag:' . $section_id]) ? 1 : 0;
						foreach ($secion['menu'] as $menu_id => $menu) {
							if (!in_array($menu['permission_name'], $user_permission) && 'platform_module' != $section_id && 'phoneapp_profile' != $section_id) {
								$frames[$nav_id]['section'][$section_id]['menu'][$menu_id]['is_display'] = false;
							} else {
								$section_show = true;
							}
						}
						if (!isset($frames[$nav_id]['section'][$section_id]['is_display'])) {
							$frames[$nav_id]['section'][$section_id]['is_display'] = $section_show;
						}
					}
				
			}

			if (ACCOUNT_MANAGE_NAME_EXPIRED == $_W['role'] && ('store' != $nav_id || 'system' != $nav_id)) {
				$menu['is_display'] = 0;
			}
		}
	} else {
		if (user_is_vice_founder()) {
			$frames['system']['section']['article']['is_display'] = false;
			$frames['system']['section']['welcome']['is_display'] = false;
			$frames['system']['section']['wxplatform']['menu']['system_platform']['is_display'] = false;
			$frames['system']['section']['user']['menu']['system_user_founder_group']['is_display'] = false;
		}
	}

		if (defined('FRAME') && (!in_array(FRAME, array('account', 'wxapp')))) {
		$frames = frames_top_menu($frames);

		return $frames[$framename];
	}

	if (defined('FRAME') && FRAME == 'account') {
		$modules = uni_modules();
		$sysmodules = module_system();
		$status = permission_account_user_permission_exist($_W['uid'], $_W['uniacid']);
		$module_permission = permission_account_user_menu($_W['uid'], $_W['uniacid'], 'modules');
				if (!$_W['isfounder'] && $status && ACCOUNT_MANAGE_NAME_OWNER != $_W['role'] && 'all' != current($module_permission)) {
			if (!is_error($module_permission) && !empty($module_permission)) {
				foreach ($module_permission as $module) {
					if (!in_array($module['type'], $sysmodules) && $modules[$module['type']][MODULE_SUPPORT_ACCOUNT_NAME] == 2) {
						$module = $modules[$module['type']];
						if (!empty($module)) {
							$frames[FRAME]['section']['platform_module']['menu']['platform_' . $module['name']] = array(
								'title' => $module['title'],
								'icon' => $module['logo'],
								'url' => url('home/welcome/account_ext', array('m' => $module['name'])),
								'is_display' => 1,
							);
						}
					}
				}
			} else {
				$frames[FRAME]['section']['platform_module']['is_display'] = false;
			}
		} else {
						$account_module = pdo_getall('uni_account_modules', array('uniacid' => $_W['uniacid'], 'shortcut' => STATUS_ON), array('module'), '', 'displayorder DESC, id DESC');
			if (!empty($account_module)) {
				foreach ($account_module as $module) {
					if (!in_array($module['module'], $sysmodules)) {
						$module = module_fetch($module['module']);
						if (!empty($module) && !empty($modules[$module['name']]) && (2 == $module[MODULE_SUPPORT_ACCOUNT_NAME] || 2 == $module['webapp_support'])) {
							$frames[FRAME]['section']['platform_module']['menu']['platform_' . $module['name']] = array(
								'title' => $module['title'],
								'icon' => $module['logo'],
								'url' => url('home/welcome/account_ext', array('m' => $module['name'])),
								'is_display' => 1,
							);
						}
					}
				}
			} elseif (!empty($modules)) {
				$new_modules = array_reverse($modules);
				$i = 0;
				foreach ($new_modules as $module) {
					if (!empty($module['issystem'])) {
						continue;
					}
					if (5 == $i) {
						break;
					}
					$frames[FRAME]['section']['platform_module']['menu']['platform_' . $module['name']] = array(
						'title' => $module['title'],
						'icon' => $module['logo'],
						'url' => url('home/welcome/account_ext', array('m' => $module['name'])),
						'is_display' => 1,
					);
					++$i;
				}
			}
			if (array_diff(array_keys($modules), $sysmodules)) {
				$frames[FRAME]['section']['platform_module']['menu']['platform_module_more'] = array(
					'title' => '更多应用',
					'url' => url('module/manage-account'),
					'is_display' => 1,
				);
			} else {
				$frames[FRAME]['section']['platform_module']['is_display'] = false;
			}
		}
	}
		$modulename = trim($_GPC['m']);
	$eid = intval($_GPC['eid']);
	$version_id = intval($_GPC['version_id']);
	if ((!empty($modulename) || !empty($eid)) && !in_array($modulename, module_system())) {
		if (!empty($eid)) {
			$entry = pdo_get('modules_bindings', array('eid' => $eid));
		}
		if (empty($modulename)) {
			$modulename = $entry['module'];
		}
		$module = module_fetch($modulename);
		if (defined('SYSTEM_WELCOME_MODULE')) {
			$entries = module_entries($modulename, array('system_welcome'));
		} else {
			$entries = module_entries($modulename);
		}
		if ($status) {
			$permission = pdo_get('users_permission', array('uniacid' => $_W['uniacid'], 'uid' => $_W['uid'], 'type' => $modulename), array('permission'));
			$module_permissions = permission_account_user_menu($_W['uid'], $_W['uniacid'], 'modules');
			if (!empty($permission)) {
				$permission = explode('|', $permission['permission']);
			} else {
				$permission = array('account*');
			}
			if ('all' != $permission[0] && 'all' != $module_permissions[0]) {
				if (!in_array($modulename . '_rule', $permission)) {
					unset($module['isrulefields']);
				}
				if (!in_array($modulename . '_settings', $permission)) {
					unset($module['settings']);
				}
				if (!in_array($modulename . '_permissions', $permission)) {
					unset($module['permissions']);
				}
				if (!in_array($modulename . '_home', $permission)) {
					unset($entries['home']);
				}
				if (!in_array($modulename . '_profile', $permission)) {
					unset($entries['profile']);
				}
				if (!in_array($modulename . '_shortcut', $permission)) {
					unset($entries['shortcut']);
				}
				if (!empty($entries['cover'])) {
					foreach ($entries['cover'] as $k => $row) {
						if (!in_array($modulename . '_cover_' . $row['do'], $permission)) {
							unset($entries['cover'][$k]);
						}
					}
				}
				if (!empty($entries['menu'])) {
					foreach ($entries['menu'] as $k => $row) {
						if ($row['multilevel']) {
							continue;
						}
						if (!in_array($modulename . '_menu_' . $row['do'], $permission)) {
							unset($entries['menu'][$k]);
						}
					}
				}
			}
		}

		$frames['account']['section'] = array();

		if (!defined('SYSTEM_WELCOME_MODULE')) {
			$frames['account']['section']['platform_module_common']['menu']['platform_module_welcome'] = array(
				'title' => '模块首页',
				'icon' => 'wi wi-home',
				'url' => url('module/welcome', array('m' => $modulename, 'uniacid' => $_GPC['uniacid'])),
				'is_display' => empty($module['main_module']) ? true : false,
				'module_welcome_display' => true,
			);
		}
		if ($module['isrulefields'] || !empty($entries['cover']) || !empty($entries['mine'])) {
			if (!empty($module['isrulefields']) && !empty($_W['account']) && in_array($_W['account']['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH, ACCOUNT_TYPE_XZAPP_NORMAL, ACCOUNT_TYPE_XZAPP_AUTH))) {
				$url = url('platform/reply', array('m' => $modulename, 'version_id' => $version_id));
			}
			if (empty($url) && !empty($entries['cover'])) {
				$url = url('platform/cover', array('eid' => $entries['cover'][0]['eid'], 'version_id' => $version_id));
			}
			$frames['account']['section']['platform_module_common']['menu']['platform_module_entry'] = array(
				'title' => '应用入口',
				'icon' => 'wi wi-reply',
				'url' => $url,
				'is_display' => 1,
			);
		}
		if ($module['settings']) {
			$frames['account']['section']['platform_module_common']['menu']['platform_module_settings'] = array(
				'title' => '参数设置',
				'icon' => 'wi wi-parameter-setting',
				'url' => url('module/manage-account/setting', array('m' => $modulename, 'version_id' => $version_id)),
				'is_display' => 1,
			);
		}

		$account_user_role = permission_account_user_role($_W['uid'], $_W['uniacid']); 		if ($module['permissions'] && ($_W['isfounder'] || ACCOUNT_MANAGE_NAME_OWNER == $account_user_role) && !defined('SYSTEM_WELCOME_MODULE')) {
			$frames['account']['section']['platform_module_common']['menu']['platform_module_permissions'] = array(
				'title' => '操作员权限',
				'icon' => 'wi wi-custommenu',
				'url' => url('module/permission', array('m' => $modulename, 'version_id' => $version_id)),
				'is_display' => 1,
			);
		}
		if ($entries['home'] && !empty($_W['account']) && in_array($_W['account']['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH))) {
			$frames['account']['section']['platform_module_common']['menu']['platform_module_home'] = array(
				'title' => '微站首页导航',
				'icon' => 'wi wi-crontab',
				'url' => url('site/nav/home', array('m' => $modulename, 'version_id' => $version_id)),
				'is_display' => 1,
			);
		}
		if ($entries['profile'] && !empty($_W['account']) && in_array($_W['account']['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH))) {
			$frames['account']['section']['platform_module_common']['menu']['platform_module_profile'] = array(
				'title' => '个人中心导航',
				'icon' => 'wi wi-user',
				'url' => url('site/nav/profile', array('m' => $modulename, 'version_id' => $version_id)),
				'is_display' => 1,
			);
		}
		if ($entries['shortcut'] && !empty($_W['account']) && in_array($_W['account']['type'], array(ACCOUNT_TYPE_OFFCIAL_NORMAL, ACCOUNT_TYPE_OFFCIAL_AUTH))) {
			$frames['account']['section']['platform_module_common']['menu']['platform_module_shortcut'] = array(
				'title' => '快捷菜单',
				'icon' => 'wi wi-plane',
				'url' => url('site/nav/shortcut', array('m' => $modulename, 'version_id' => $version_id)),
				'is_display' => 1,
			);
		}
		if (!empty($entries['cover'])) {
			foreach ($entries['cover'] as $key => $menu) {
				$frames['account']['section']['platform_module_common']['menu']['platform_module_cover'][] = array(
					'title' => "{$menu['title']}",
					'url' => url('platform/cover', array('eid' => $menu['eid'], 'version_id' => $version_id)),
					'is_display' => 0,
				);
			}
		}

		
		$version_modules = array();
		if ($_W['account']->supportVersion) {
			$version_modules = miniapp_version($version_id);
			$version_modules = $version_modules['modules'];
		}

		if (!empty($module['plugin_list']) || !empty($module['main_module'])) {
			$modules = uni_modules();
			if (!empty($module['main_module'])) {
				$main_module = module_fetch($module['main_module']);
				$plugin_list = $main_module['plugin_list'];
			} else {
				$plugin_list = $module['plugin_list'];
			}
			$plugin_list = array_intersect($plugin_list, array_keys($modules));
		}

		if (!empty($module['plugin_list']) && empty($module['main_module'])) {
			$frames['account']['section']['platform_module_plugin']['title'] = '常用插件';
						$module_menu_plugin_list = table('core_menu_shortcut')->getCurrentModuleMenuPluginList($module['name']);
			if (!empty($module_menu_plugin_list)) {
				$plugin_list = array_keys($module_menu_plugin_list);
			}

			if (!empty($plugin_list)) {
				$i = 0;
				foreach ($plugin_list as $plugin_module) {
					$plugin_module_info = module_fetch($plugin_module);
					if (3 == $i && empty($module_menu_plugin_list)) {
						break;
					}
					$frames['account']['section']['platform_module_plugin']['menu']['platform_' . $plugin_module_info['name']] = array(
						'main_module' => $plugin_module_info['main_module'],
						'title' => $plugin_module_info['title'],
						'icon' => $plugin_module_info['logo'],
						'url' => url('home/welcome/ext', array('m' => $plugin_module_info['name'], 'uniacid' => $_W['uniacid'], 'version_id' => $version_id)),
						'is_display' => 1,
					);
					++$i;
				}
			}

			if ($module['main_module']) {
				$platform_module_plugin_more_url = url('module/plugin', array('m' => $module['main_module'], 'uniacid' => $_W['uniacid']));
			} else {
				$platform_module_plugin_more_url = url('module/plugin', array('m' => $module['name'], 'uniacid' => $_W['uniacid']));
			}

			if (!empty($plugin_list)) {
				$frames['account']['section']['platform_module_plugin']['menu']['platform_module_plugin_more'] = array(
					'title' => '更多插件',
					'url' => $platform_module_plugin_more_url,
					'is_display' => empty($module['main_module']) ? 1 : 0,
				);
			} else {
				$frames['account']['section']['platform_module_plugin']['is_display'] = false;
			}
		}

		if (!empty($entries['menu'])) {
			$frames['account']['section']['platform_module_menu']['title'] = '业务菜单';
			foreach ($entries['menu'] as $key => $row) {
				if (empty($row)) {
					continue;
				}
				if (!empty($row['parent']) && !empty($frames['account']['section']['platform_module_menu']['menu']['platform_module_menu' . $row['parent']])) {
					$frames['account']['section']['platform_module_menu']['menu']['platform_module_menu' . $row['parent']]['childs'][] = array(
						'title' => $row['title'],
						'url' => $row['url'] . '&version_id=' . $version_id,
						'icon' => empty($row['icon']) ? 'wi wi-appsetting' : $row['icon'],
						'is_display' => 1,
					);
					continue;
				}
								if (!empty($row['from']) && 'call' == $row['from']) {
					$frames['account']['section']['platform_module_menu']['menu']['platform_module_menu' . $row['eid']] = array(
						'title' => $row['title'],
						'url' => $row['url'] . '&version_id=' . $version_id,
						'icon' => empty($row['icon']) ? 'wi wi-appsetting' : $row['icon'],
						'is_display' => 1,
					);
				} else {
					$frames['account']['section']['platform_module_menu']['menu']['platform_module_menu' . $row['do']] = array(
						'title' => $row['title'],
						'url' => $row['url'] . '&version_id=' . $version_id,
						'icon' => empty($row['icon']) ? 'wi wi-appsetting' : $row['icon'],
						'is_display' => 1,
						'multilevel' => $row['multilevel'],
					);
				}
			}

			foreach ($frames['account']['section']['platform_module_menu']['menu'] as $key => $row) {
				if (!empty($row['multilevel']) && empty($row['childs'])) {
					unset($frames['account']['section']['platform_module_menu']['menu'][$key]);
				}
			}
		}
		
			if (!empty($entries['system_welcome']) && $_W['isfounder']) {
				$frames['account']['section']['platform_module_welcome']['title'] = '';
				foreach ($entries['system_welcome'] as $key => $row) {
					if (empty($row)) {
						continue;
					}
					$frames['account']['section']['platform_module_welcome']['menu']['platform_module_welcome' . $row['eid']] = array(
						'title' => "<i class='wi wi-appsetting'></i> {$row['title']}",
						'url' => $row['url'],
						'is_display' => 1,
					);
				}
			}
		
	}

		if (defined('FRAME') && FRAME == 'wxapp') {
		load()->model('miniapp');
		$version_id = intval($_GPC['version_id']);
		$wxapp_version = miniapp_version($version_id);
		if (!empty($wxapp_version['last_modules']) && is_array($wxapp_version['last_modules'])) {
			$last_modules = current($wxapp_version['last_modules']);
		}

		if (!empty($wxapp_version['modules'])) {
			foreach ($wxapp_version['modules'] as $module) {
				$wxapp_module_permission = permission_account_user_menu($_W['uid'], $_W['uniacid'], $module['name']);
				if (empty($wxapp_module_permission)) {
					$frames['wxapp']['section']['platform_module']['is_display'] = false;
					break;
				}
				$need_upload = !empty($last_modules) && ($module['version'] != $last_modules['version']);
				if (!empty($module) && (isset($module['recycle_info']) ? (empty($module['recycle_info']['wxapp_support']) ? true : false) : true)) {
					$frames['wxapp']['section']['platform_module']['menu']['module_menu' . $module['mid']] = array(
						'icon' => $module['logo'],
						'title' => $module['title'],
						'url' => url('home/welcome/account_ext', array('m' => $module['name'], 'version_id' => $version_id)),
						'is_display' => 1,
					);
				}
			}
		} else {
			$frames['wxapp']['section']['platform_module']['is_display'] = false;
		}
		if (!empty($frames['wxapp']['section']['wxapp_profile']['menu']['front_download'])) {
			$frames['wxapp']['section']['wxapp_profile']['menu']['front_download']['need_upload'] = empty($need_upload) ? 0 : 1;
		}

		if (!empty($frames['wxapp']['section'])) {
			$wxapp_permission = permission_account_user('wxapp');
			$status = permission_account_user_permission_exist($_W['uid'], $_W['uniacid']);
			foreach ($frames['wxapp']['section'] as $wxapp_section_id => $wxapp_section) {
				if ($status && !empty($wxapp_permission) && in_array('wxapp*', $wxapp_permission) && 'platform_module' != $wxapp_section_id && ACCOUNT_MANAGE_NAME_OWNER != $role) {
					$frames['wxapp']['section'][$wxapp_section_id]['is_display'] = false;
					continue;
				}
				if (!empty($wxapp_section['menu']) && 'platform_module' != $wxapp_section_id) {
					foreach ($wxapp_section['menu'] as $wxapp_menu_id => $wxapp_menu) {
						if (in_array($wxapp_section_id, array('wxapp_profile', 'wxapp_entrance', 'statistics', 'mc'))) {
							$frames['wxapp']['section'][$wxapp_section_id]['menu'][$wxapp_menu_id]['url'] .= 'version_id=' . $version_id;
						}
						if (!in_array('wxapp*', $wxapp_permission) && !in_array($wxapp_menu['permission_name'], $wxapp_permission)) {
							$frames['wxapp']['section'][$wxapp_section_id]['menu'][$wxapp_menu_id]['is_display'] = false;
						}
					}
				}
			}
		}
	}
	$frames = frames_top_menu($frames);

	return !empty($framename) ? ('system_welcome' == $framename ? $frames['account'] : $frames[$framename]) : $frames;
}

function frames_top_menu($frames) {
	global $_W, $top_nav;
	if (empty($frames)) {
		return array();
	}
		$is_vice_founder = user_is_vice_founder();
	$founders = explode(',', $_W['config']['setting']['founder']);
	foreach ($frames as $menuid => $menu) {
		if ((!empty($menu['founder']) || in_array($menuid, array('module_manage', 'site', 'advertisement', 'appmarket'))) && !in_array($_W['uid'], $founders) ||
			ACCOUNT_MANAGE_NAME_CLERK == $_W['highest_role'] && in_array($menuid, array('account', 'wxapp', 'system', 'platform', 'welcome', 'account_manage')) ||
			!$is_vice_founder && !in_array($_W['uid'], $founders) && in_array($menuid, array('user_manage', 'permission')) ||
			'myself' == $menuid && in_array($_W['uid'], $founders) ||
			!$menu['is_display']) {
			continue;
		}

		
			if (is_array($_W['setting']['store']['blacklist']) && in_array($_W['username'], $_W['setting']['store']['blacklist']) && !empty($_W['setting']['store']['permission_status']) && $_W['setting']['store']['permission_status']['blacklist'] && 'store' == $menuid ||
				is_array($_W['setting']['store']['whitelist']) && !in_array($_W['username'], $_W['setting']['store']['whitelist']) && !empty($_W['setting']['store']['permission_status']) && $_W['setting']['store']['permission_status']['whitelist'] && !($_W['isfounder'] && !$is_vice_founder) && 'store' == $menuid ||
				$_W['setting']['store']['status'] == 1 && 'store' == $menuid && !in_array($_W['uid'], $founders)) {
				continue;
			}
		

		$top_nav[] = array(
			'title' => $menu['title'],
			'name' => $menuid,
			'url' => $menu['url'],
			'blank' => $menu['blank'],
			'icon' => $menu['icon'],
			'is_display' => $menu['is_display'],
			'is_system' => $menu['is_system'],
		);
	}

	return $frames;
}


function filter_url($params) {
	global $_W;
	if (empty($params)) {
		return '';
	}
	$query_arr = array();
	$parse = parse_url($_W['siteurl']);
	if (!empty($parse['query'])) {
		$query = $parse['query'];
		parse_str($query, $query_arr);
	}
	$params = explode(',', $params);
	foreach ($params as $val) {
		if (!empty($val)) {
			$data = explode(':', $val);
			$query_arr[$data[0]] = trim($data[1]);
		}
	}
	$query_arr['page'] = 1;
	$query = http_build_query($query_arr);

	return './index.php?' . $query;
}

function url_params($url) {
	$result = array();
	if (empty($url)) {
		return $result;
	}
	$components = parse_url($url);
	$params = explode('&', $components['query']);
	foreach ($params as $param) {
		if (!empty($param)) {
			$param_array = explode('=', $param);
			$result[$param_array[0]] = $param_array[1];
		}
	}

	return $result;
}

function frames_menu_append() {
		$system_menu_default_permission = array(
		'founder' => array(),
		'vice_founder' => array(
			'system_setting_updatecache',
		),
		'owner' => array(
			'system_setting_updatecache',
		),
		'manager' => array(
			'system_setting_updatecache',
		),
		'operator' => array(
			'system_setting_updatecache',
		),
		'clerk' => array(),
		'expired' => array(
			'system_setting_updatecache',
		),
	);

	return $system_menu_default_permission;
}


function site_profile_perfect_tips() {
	global $_W;

	if ($_W['isfounder'] && (empty($_W['setting']['site']) || empty($_W['setting']['site']['profile_perfect']))) {
		if (!defined('SITE_PROFILE_PERFECT_TIPS')) {
			$url = url('cloud/profile');

			return <<<EOF
$(function() {
	var html =
		'<div class="we7-body-alert">'+
			'<div class="container">'+
				'<div class="alert alert-info">'+
					'<i class="wi wi-info-sign"></i>'+
					'<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true" class="wi wi-error-sign"></span><span class="sr-only">Close</span></button>'+
					'<a href="{$url}" target="_blank">请尽快完善您在微擎云服务平台的站点注册信息。</a>'+
				'</div>'+
			'</div>'+
		'</div>';
	$('body').prepend(html);
});
EOF;
			define('SITE_PROFILE_PERFECT_TIPS', true);
		}
	}

	return '';
}

function strtoutf8($str) {
	$current_encode = mb_detect_encoding($str, array('ASCII', 'GB2312', 'GBK', 'BIG5', 'UTF-8'));
	return mb_convert_encoding($str, 'UTF-8', $current_encode);
}