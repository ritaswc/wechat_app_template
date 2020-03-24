<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

function miniapp_getpackage($data, $if_single = false) {
	load()->classs('cloudapi');

	$api = new CloudApi();
	$result = $api->post('wxapp', 'download', $data, 'html');
	if (is_error($result)) {
		return error(-1, $result['message']);
	} else {
		if (strpos($result, 'error:') === 0) {
			return error(-1, substr($result, 6));
		}
	}

	return $result;
}


function miniapp_create($account) {
	global $_W;
	load()->model('account');
	load()->model('user');
	load()->model('permission');

	$account_type_info = uni_account_type($account['type']);
	if (empty($account['type']) || empty($account_type_info['support_version'])) {
		return error(1, '账号类型错误!');
	}
	$uni_account_data = array(
		'name' => $account['name'],
		'description' => $account['description'],
		'title_initial' => get_first_pinyin($account['name']),
		'groupid' => 0,
		'createtime' => TIMESTAMP,
	);
	if (!pdo_insert('uni_account', $uni_account_data)) {
		return error(1, '添加失败');
	}
	$uniacid = pdo_insertid();
	$account_data = array(
		'uniacid' => $uniacid,
		'type' => $account['type'],
		'hash' => random(8),
	);
	pdo_insert('account', $account_data);
	$acid = pdo_insertid();
	pdo_update('uni_account', array('default_acid' => $acid), array('uniacid' => $uniacid));

	load()->model('utility');
	if (!empty($account['headimg'])) {
		utility_image_rename($account['headimg'], ATTACHMENT_ROOT . 'headimg_' . $acid . '.jpg');
	}
	if (!empty($account['qrcode'])) {
		utility_image_rename($account['qrcode'], ATTACHMENT_ROOT . 'qrcode_' . $acid . '.jpg');
	}

	if (in_array($account['type'], array(ACCOUNT_TYPE_APP_NORMAL, ACCOUNT_TYPE_APP_AUTH))) {
		$data = array(
			'acid' => $acid,
			'token' => isset($account['token']) ? $account['token'] : random(32),
			'encodingaeskey' => isset($account['encodingaeskey']) ? $account['encodingaeskey'] : random(43),
			'auth_refresh_token' => isset($account['auth_refresh_token']) ? $account['auth_refresh_token'] : '',
			'uniacid' => $uniacid,
			'name' => $account['name'],
			'original' => $account['original'],
			'level' => $account['level'],
			'key' => $account['key'],
			'secret' => $account['secret'],
		);

		if (empty($_W['isfounder'])) {
			$user_info = permission_user_account_num($_W['uid']);
			if (empty($user_info['usergroup_wxapp_limit'])) {
				pdo_update('account', array('endtime' => strtotime('+1 month', time())), array('uniacid' => $uniacid));
				pdo_insert('site_store_create_account', array('endtime' => strtotime('+1 month', time()), 'uid' => $_W['uid'], 'uniacid' => $uniacid, 'type' => ACCOUNT_TYPE_APP_NORMAL));
			}
		}
	} else {
		$data = array(
			'acid' => $acid,
			'uniacid' => $uniacid,
			'name' => $account['name'],
			'description' => $account['description'],
		);
				if (isset($account['secret']) && !empty($account['secret'])) {
			$data['secret'] = $account['secret'];
		}
		if (isset($account['key']) && !empty($account['key'])) {
			$data['key'] = $account['key'];
		}
		if (isset($account['appid']) && !empty($account['appid'])) {
			$data['appid'] = $account['appid'];
		}
		if (isset($account['level']) && !empty($account['level'])) {
			$data['level'] = $account['level'];
		}
	}

	pdo_insert($account_type_info['table_name'], $data);
	if (empty($_W['isfounder'])) {
		uni_user_account_role($uniacid, $_W['uid'], ACCOUNT_MANAGE_NAME_OWNER);
	}
	if (user_is_vice_founder()) {
		uni_user_account_role($uniacid, $_W['uid'], ACCOUNT_MANAGE_NAME_VICE_FOUNDER);
	}
	if (!empty($_W['user']['owner_uid'])) {
		uni_user_account_role($uniacid, $_W['user']['owner_uid'], ACCOUNT_MANAGE_NAME_VICE_FOUNDER);
	}
	$unisettings['creditnames'] = array('credit1' => array('title' => '积分', 'enabled' => 1), 'credit2' => array('title' => '余额', 'enabled' => 1));
	$unisettings['creditnames'] = iserializer($unisettings['creditnames']);
	$unisettings['creditbehaviors'] = array('activity' => 'credit1', 'currency' => 'credit2');
	$unisettings['creditbehaviors'] = iserializer($unisettings['creditbehaviors']);
	$unisettings['uniacid'] = $uniacid;
	pdo_insert('uni_settings', $unisettings);

	return $uniacid;
}


function miniapp_support_wxapp_modules($uniacid = 0) {
	global $_W;
	$uniacid = empty($uniacid) ? $_W['uniacid'] : intval($uniacid);
	$modules = uni_modules_by_uniacid($uniacid);
	if (empty($modules)) {
		return array();
	}
	$wxapp_modules = array();
	foreach ($modules as $module) {
		if ($module['wxapp_support'] == MODULE_SUPPORT_WXAPP) {
			$wxapp_modules[$module['name']] = $module;
		}
	}
	if (empty($wxapp_modules)) {
		return array();
	}
	$bindings = pdo_getall('modules_bindings', array('module' => array_keys($wxapp_modules), 'entry' => 'page'));
	if (!empty($bindings)) {
		foreach ($bindings as $bind) {
			$wxapp_modules[$bind['module']]['bindings'][] = array('title' => $bind['title'], 'do' => $bind['do']);
		}
	}
	return $wxapp_modules;
}


function miniapp_support_uniacid_modules($uniacid) {
	$uni_modules = uni_modules_by_uniacid($uniacid);
	if (!empty($uni_modules)) {
		foreach ($uni_modules as $module_name => $module_info) {
			if ($module_info['issystem'] == 1) {
				unset($uni_modules[$module_name]);
			}
		}
	}
	return $uni_modules;
}


function miniapp_fetch($uniacid, $version_id = '') {
	global $_GPC;
	load()->model('extension');
	$miniapp_info = array();
	$version_id = max(0, intval($version_id));
	$account_extra_info = uni_account_extra_info($uniacid);
	if (empty($account_extra_info)) {
		return $miniapp_info;
	}
	$miniapp_info = pdo_get($account_extra_info['table_name'], array('uniacid' => $uniacid));
	if (empty($miniapp_info)) {
		return $miniapp_info;
	}

	if (empty($version_id)) {
		$miniapp_cookie_uniacids = array();
		if (!empty($_GPC['__miniappversionids' . $uniacid])) {
			$miniappversionids = json_decode(htmlspecialchars_decode($_GPC['__miniappversionids' . $uniacid]), true);
			foreach ($miniappversionids as $version_val) {
				$miniapp_cookie_uniacids[] = $version_val['uniacid'];
			}
		}
		if (in_array($uniacid, $miniapp_cookie_uniacids)) {
			$miniapp_version_info = miniapp_version($miniappversionids[$uniacid]['version_id']);
		}

		if (empty($miniapp_version_info)) {
			$sql = 'SELECT * FROM ' . tablename($account_extra_info['version_tablename']) . ' WHERE `uniacid`=:uniacid ORDER BY `id` DESC';
			$miniapp_version_info = pdo_fetch($sql, array(':uniacid' => $uniacid));
		}
	} else {
		$miniapp_version_info = pdo_get($account_extra_info['version_tablename'], array('id' => $version_id, 'uniacid' => $uniacid));
	}
	if (!empty($miniapp_version_info) && !empty($miniapp_version_info['modules'])) {

		$miniapp_version_info['modules'] = iunserializer($miniapp_version_info['modules']);
				if ($miniapp_version_info['design_method'] == WXAPP_MODULE) {
			$module = current($miniapp_version_info['modules']);
			$manifest = ext_module_manifest($module['name']);
			if (!empty($manifest)) {
				$miniapp_version_info['modules'][$module['name']]['version'] = $manifest['application']['version'];
			} else {
				$last_install_module = module_fetch($module['name']);
				$miniapp_version_info['modules'][$module['name']]['version'] = $last_install_module['version'];
			}
		}
	}
	$miniapp_info['version'] = $miniapp_version_info;
	$miniapp_info['version_num'] = explode('.', $miniapp_version_info['version']);

	return  $miniapp_info;
}

function miniapp_version_all($uniacid) {
	load()->model('module');
	$miniapp_versions = array();
	$uniacid = intval($uniacid);

	if (empty($uniacid)) {
		return $miniapp_versions;
	}

	$miniapp_versions = table('wxapp_versions')->getAllByUniacid($uniacid);

	if (!empty($miniapp_versions)) {
		foreach ($miniapp_versions as &$version) {
			$version = miniapp_version($version['id']);
		}
		unset($version);
	}
	return $miniapp_versions;
}


function miniapp_get_some_lastversions($uniacid) {
	$version_lasts = array();
	$uniacid = intval($uniacid);

	if (empty($uniacid)) {
		return $version_lasts;
	}
	$version_lasts = table('wxapp_versions')->latestVersion($uniacid);
	$last_switch_version = miniapp_last_switch_version($uniacid);
	if (!empty($last_switch_version[$uniacid]) && !empty($version_lasts[$last_switch_version[$uniacid]['version_id']])) {
		$version_lasts[$last_switch_version[$uniacid]['version_id']]['current'] = true;
	} else {
		reset($version_lasts);
		$firstkey = key($version_lasts);
		$version_lasts[$firstkey]['current'] = true;
	}

	return $version_lasts;
}


function miniapp_last_switch_version($uniacid) {
	global $_GPC;
	static $miniapp_cookie_uniacids;
	if (empty($miniapp_cookie_uniacids) && !empty($_GPC['__miniappversionids' . $uniacid])) {
		$miniapp_cookie_uniacids = json_decode(htmlspecialchars_decode($_GPC['__miniappversionids' . $uniacid]), true);
	}

	return $miniapp_cookie_uniacids;
}


function miniapp_update_last_use_version($uniacid, $version_id) {
	global $_GPC;
	$uniacid = intval($uniacid);
	$version_id = intval($version_id);
	if (empty($uniacid) || empty($version_id)) {
		return false;
	}
	$cookie_val = array();
	if (!empty($_GPC['__miniappversionids' . $uniacid])) {
		$miniapp_uniacids = array();
		$cookie_val = json_decode(htmlspecialchars_decode($_GPC['__miniappversionids' . $uniacid]), true);
		if (!empty($cookie_val)) {
			foreach ($cookie_val as &$version) {
				$miniapp_uniacids[] = $version['uniacid'];
				if ($version['uniacid'] == $uniacid) {
					$version['version_id'] = $version_id;
					$miniapp_uniacids = array();
					break;
				}
			}
			unset($version);
		}
		if (!empty($miniapp_uniacids) && !in_array($uniacid, $miniapp_uniacids)) {
			$cookie_val[$uniacid] = array('uniacid' => $uniacid, 'version_id' => $version_id);
		}
	} else {
		$cookie_val = array(
			$uniacid => array('uniacid' => $uniacid, 'version_id' => $version_id),
		);
	}
	isetcookie('__uniacid', $uniacid, 7 * 86400);
	isetcookie('__miniappversionids' . $uniacid, json_encode($cookie_val), 7 * 86400);

	return true;
}


function miniapp_version($version_id) {
	$version_info = array();
	$version_id = intval($version_id);

	if (empty($version_id)) {
		return $version_info;
	}

		load()->classs('wxapp.account');
	$cachekey = cache_system_key('miniapp_version', array('version_id' => $version_id));
	$cache = cache_load($cachekey);
	if (!empty($cache)) {
		return $cache;
	}
	$version_info = table('wxapp_versions')->getById($version_id);
	$version_info = table('wxapp_versions')->dataunserializer($version_info);
	$version_info = miniapp_version_detail_info($version_info);
	cache_write($cachekey, $version_info);

	return $version_info;
}

function miniapp_version_detail_info($version_info) {
	if (empty($version_info) || empty($version_info['uniacid']) || empty($version_info['modules'])) {
		return $version_info;
	}

	$uni_modules = uni_modules_by_uniacid($version_info['uniacid']);
	$uni_modules = array_keys($uni_modules);

	$account = pdo_get('account', array('uniacid' => $version_info['uniacid']));
	if (in_array($account['type'], array(ACCOUNT_TYPE_APP_NORMAL, ACCOUNT_TYPE_APP_AUTH))) {
		if (!empty($version_info['modules'])) {
			foreach ($version_info['modules'] as $i => $module) {
				if (!in_array($module['name'], $uni_modules)) {
					unset($version_info['modules'][$i]);
					continue;
				}
				$module_info = module_fetch($module['name']);
				$module_info['version'] = $module['version'];
				$module['uniacid'] = table('uni_link_uniacid')->getMainUniacid($version_info['uniacid'], $module['name'], $version_info['id']);
				if (!empty($module['uniacid'])) {
					$module_info['uniacid'] = $module['uniacid'];
					$link_account = uni_fetch($module['uniacid']);
					$module_info['account'] = $link_account->account;
					$module_info['account']['logo'] = $link_account->logo;
				}
								$module_info['cover_entrys'] = module_entries($module['name'], array('cover'));
				$module_info['defaultentry'] = $module['defaultentry'];
				$module_info['newicon'] = $module['newicon'];
				$version_info['modules'][$i] = $module_info;
			}
		}
		if (count($version_info['modules']) > 0) {
			$version_module = current($version_info['modules']);
			$version_info['cover_entrys'] = !empty($version_module['cover_entrys']['cover']) ? $version_module['cover_entrys']['cover'] : array();
		}
		$version_info['last_modules'] = iunserializer($version_info['last_modules']);
		$version_info['tominiprogram'] = iunserializer($version_info['tominiprogram']);
		if (!empty($version_info['quickmenu'])) {
			$version_info['quickmenu'] = iunserializer($version_info['quickmenu']);
		}
	} else {
		foreach ($version_info['modules'] as $i => $module) {
			if (!in_array($module['name'], $uni_modules)) {
				unset($version_info['modules'][$i]);
				continue;
			}
			$module_info = module_fetch($module['name']);
			$module_info['version'] = $module['version'];
			$module['uniacid'] = table('uni_link_uniacid')->getMainUniacid($version_info['uniacid'], $module['name'], $version_info['id']);
			if (!empty($module['uniacid'])) {
				$module_info['uniacid'] = $module['uniacid'];
				$link_account = uni_fetch($module['uniacid']);
				$module_info['account'] = $link_account->account;
				$module_info['account']['logo'] = $link_account->logo;
			}
			$version_info['modules'][$i] = $module_info;
		}
	}

	return $version_info;
}


function miniapp_version_by_version($version) {
	global $_W;
	$version_info = array();
	$version = trim($version);
	if (empty($version)) {
		return $version_info;
	}
	$version_info = table('wxapp_versions')->getByUniacidAndVersion($_W['uniacid'], $version);
	$version_info = miniapp_version_detail_info($version_info);

	return $version_info;
}

function miniapp_site_info($multiid) {
	$site_info = array();
	$multiid = intval($multiid);

	if (empty($multiid)) {
		return array();
	}

	$site_info['slide'] = pdo_getall('site_slide', array('multiid' => $multiid));
	$site_info['nav'] = pdo_getall('site_nav', array('multiid' => $multiid));
	if (!empty($site_info['nav'])) {
		foreach ($site_info['nav'] as &$nav) {
			$nav['css'] = iunserializer($nav['css']);
		}
		unset($nav);
	}
	$recommend_sql = 'SELECT a.name, b.* FROM ' . tablename('site_category') . ' AS a LEFT JOIN ' . tablename('site_article') . ' AS b ON a.id = b.pcate WHERE a.parentid = 0 AND a.multiid = :multiid';
	$site_info['recommend'] = pdo_fetchall($recommend_sql, array(':multiid' => $multiid));

	return $site_info;
}

function miniapp_update_daily_visittrend() {
	global $_W;
	$yesterday = date('Ymd', strtotime('-1 days'));
	$trend = pdo_get('wxapp_general_analysis', array('uniacid' => $_W['uniacid'], 'type' => WXAPP_STATISTICS_DAILYVISITTREND, 'ref_date' => $yesterday));
	if (!empty($trend)) {
		return true;
	}
	return miniapp_insert_date_visit_trend($yesterday);
}

function miniapp_insert_date_visit_trend($date) {
	global $_W;
	$account_api = WeAccount::createByUniacid();
	$wxapp_stat = $account_api->getDailyVisitTrend($date);
	if (is_error($wxapp_stat) || empty($wxapp_stat)) {
		return error(-1, '调用微信接口错误');
	} else {
		$insert_stat = array(
			'uniacid' => $_W['uniacid'],
			'session_cnt' => $wxapp_stat['session_cnt'],
			'visit_pv' => $wxapp_stat['visit_pv'],
			'visit_uv' => $wxapp_stat['visit_uv'],
			'visit_uv_new' => $wxapp_stat['visit_uv_new'],
			'type' => WXAPP_STATISTICS_DAILYVISITTREND,
			'stay_time_uv' => $wxapp_stat['stay_time_uv'],
			'stay_time_session' => $wxapp_stat['stay_time_session'],
			'visit_depth' => $wxapp_stat['visit_depth'],
			'ref_date' => $wxapp_stat['ref_date'],
		);
		pdo_insert('wxapp_general_analysis', $insert_stat);
	}
	return $insert_stat;
}


function miniapp_code_generate($version_id, $user_version) {
	global $_W;
	load()->classs('cloudapi');
	$api = new CloudApi();
	$version_info = miniapp_version($version_id);
	$account_wxapp_info = miniapp_fetch($version_info['uniacid'], $version_id);
	if (empty($account_wxapp_info)) {
		return error(1, '版本不存在');
	}
	$siteurl = $_W['siteroot'] . 'app/index.php';
	if (!empty($account_wxapp_info['appdomain'])) {
		$siteurl = $account_wxapp_info['appdomain'];
	}
	if (!starts_with($siteurl, 'https')) { 		return error(1, '小程序域名必须为https');
	}

	if ($version_info['type'] == WXAPP_CREATE_MODULE && $version_info['entry_id'] <= 0) {
		return error(1, '请先设置小程序入口');
	}

	$appid = $account_wxapp_info['key'];
	$siteinfo = array(
		'name' => $account_wxapp_info['name'],
		'uniacid' => $account_wxapp_info['uniacid'],
		'acid' => $account_wxapp_info['acid'],
		'multiid' => $account_wxapp_info['version']['multiid'],
		'version' => $user_version,
		'siteroot' => $siteurl,
		'design_method' => $account_wxapp_info['version']['design_method'],
		'tominiprogram' => array_keys($version_info['tominiprogram']),
	);

	$commit_data = array(
		'do' => 'generate',
		'appid' => $appid,
		'modules' => $account_wxapp_info['version']['modules'],
		'siteinfo' => $siteinfo,
		'tabBar' => json_decode($account_wxapp_info['version']['quickmenu'], true),
				'wxapp_type' => isset($version_info['type']) ? $version_info['type'] : 0,
	);

	$do = 'upload';
	if ($version_info['use_default'] == 0) {
		$appjson = miniapp_code_custom_appjson_tobase64($version_id);
		if ($appjson) {
			if (!isset($appjson['tabBar']['list'])) {
				unset($appjson['tabBar']);
			}
			$commit_data['appjson'] = $appjson;
		}
	}

	$data = $api->post('wxapp', $do, $commit_data, 'json', false);
	return $data;
}


function miniapp_check_code_isgen($code_uuid) {
	load()->classs('cloudapi');
	$api = new CloudApi();
	$data = $api->get('wxapp', 'upload', array('do' => 'check_gen', 'code_uuid' => $code_uuid, ), 'json', false);
	return $data;
}


function miniapp_code_token() {
	global $_W;
	load()->classs('cloudapi');
	$cloud_api = new CloudApi();
	$data = $cloud_api->get('wxapp', 'upload', array('do' => 'code_token'), 'json', false);

	return $data;
}


function miniapp_code_qrcode($code_token) {
	$cloud_api = new CloudApi();
	$data = $cloud_api->get('wxapp', 'upload', array('do' => 'qrcode',
			'code_token' => $code_token, ),
			'html', false);

	return $data;
}


function miniapp_code_check_scan($code_token, $last) {
	$cloud_api = new CloudApi();
	$data = $cloud_api->get('wxapp',
			 				'upload',
							array('do' => 'checkscan', 'code_token' => $code_token, 'last' => $last),
							'json',
							false);
	return $data;
}


function miniapp_code_preview_qrcode($code_uuid, $code_token) {
	$cloud_api = new CloudApi();

	$commit_data = array(
			'do' => 'preview_qrcode',
			'code_uuid' => $code_uuid,
			'code_token' => $code_token,
	);
	$data = $cloud_api->post('wxapp', 'upload', $commit_data,
			'json', false);

	return $data;
}

function miniapp_code_commit($code_uuid, $code_token, $user_version = 3, $user_desc = '代码提交') {
	$cloud_api = new CloudApi();

	$commit_data = array(
			'do' => 'commitcode',
			'code_uuid' => $code_uuid,
			'code_token' => $code_token,
			'user_version' => $user_version,
			'user_desc' => $user_desc,
	);
	$data = $cloud_api->post('wxapp', 'upload', $commit_data,
			'json', false);

	return $data;
}


function miniapp_update_entry($version_id, $entry_id) {
	$result = pdo_update('wxapp_versions', array('entry_id' => $entry_id), array('id' => $version_id));
	cache_delete(cache_system_key('miniapp_version', array('version_id' => $version_id)));
	return $result;
}


function miniapp_code_current_appjson($version_id) {
	load()->classs('cloudapi');
	$version_info = miniapp_version($version_id);
		if (!$version_info['use_default'] && isset($version_info['appjson'])) {
		return iunserializer($version_info['appjson']);
	}
		if ($version_info['use_default']) {
		$appjson = $version_info['default_appjson'];
		if ($appjson) {
			return iunserializer($appjson);
		}
				$cloud_api = new CloudApi();
		$account_wxapp_info = miniapp_fetch($version_info['uniacid'], $version_id);
		$commit_data = array('do' => 'appjson',
				'wxapp_type' => isset($version_info['type']) ? $version_info['type'] : 0,
				'modules' => $account_wxapp_info['version']['modules'],
		);
		$cloud_appjson = $cloud_api->get('wxapp', 'upload', $commit_data,
				'json', false);
		if (is_error($cloud_appjson)) { 			return null;
		}
		$appjson = $cloud_appjson['data']['appjson'];
		pdo_update('wxapp_versions', array('default_appjson' => serialize($appjson)), array('id' => $version_id));
		cache_delete(cache_system_key('miniapp_version', array('version_id' => $version_id)));
		return $appjson;
	}
}


function miniapp_code_custom_appjson_tobase64($version_id) {
	load()->classs('image');
	$version_info = miniapp_version($version_id);
	$appjson = iunserializer($version_info['appjson']);
	if (!$appjson) {
		return false;
	}
	if (isset($appjson['tabBar']) && isset($appjson['tabBar']['list'])) {
		$tablist = &$appjson['tabBar']['list'];
		foreach ($tablist as &$item) {
						if (isset($item['iconPath']) && !starts_with($item['iconPath'], 'data:image')) {
				$item['iconPath'] = Image::create($item['iconPath'])->resize(81, 81)->toBase64();
			}
			if (isset($item['selectedIconPath']) && !starts_with($item['selectedIconPath'], 'data:image')) {
				$item['selectedIconPath'] = Image::create($item['selectedIconPath'])->resize(81, 81)->toBase64();
			}
		}
	}

	return $appjson;
}


function miniapp_code_path_convert($attachment_id) {
	load()->classs('image');
	load()->func('file');

	$attchid = intval($attachment_id);
	global $_W;
	$attachment = table('core_attachment')->getById($attchid);
	if ($attachment) {
		$attach_path = $attachment['attachment'];
		$ext = pathinfo($attach_path, PATHINFO_EXTENSION);
		$url = tomedia($attach_path);
		$uniacid = intval($_W['uniacid']);
		$path = "images/{$uniacid}/" . date('Y/m/');
		mkdirs($path);
		$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, $ext);
		Image::create($url)->resize(81, 81)->saveTo(ATTACHMENT_ROOT . $path . $filename);
		$attachdir = $_W['config']['upload']['attachdir'];

		return $_W['siteroot'] . $attachdir . '/' . $path . $filename;
	}

	return null;
}


function miniapp_code_save_appjson($version_id, $json) {
	$result = pdo_update('wxapp_versions', array('appjson' => serialize($json), 'use_default' => 0), array('id' => $version_id));
	cache_delete(cache_system_key('miniapp_version', array('version_id' => $version_id)));
	return $result;
}


function miniapp_code_set_default_appjson($version_id) {
	$result = pdo_update('wxapp_versions', array('appjson' => '', 'use_default' => 1), array('id' => $version_id));
	cache_delete(cache_system_key('miniapp_version', array('version_id' => $version_id)));
	return $result;
}

function miniapp_version_update($version_id, $data) {
	$result = table('wxapp_versions')->fill($data)->where('id', $version_id)->save();
	cache_delete(cache_system_key('miniapp_version', array('version_id' => $version_id)));
	return $result;
}