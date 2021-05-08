<?php

defined('IN_IA') or exit('Access Denied');

function phoneapp_support_modules() {
	global $_W;
	load()->model('user');
	$modules = user_modules($_W['uid']);
	$phoneapp_modules = array();
	if (!empty($modules)) {
		foreach ($modules as $module) {
			if ($module['phoneapp_support'] == MODULE_SUPPORT_PHONEAPP) {
				$phoneapp_modules[$module['name']] = $module;
			}
		}
	}
	return $phoneapp_modules;
}



function phoneapp_get_some_lastversions($uniacid) {
	load()->model('miniapp');
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


function phoneapp_version_by_version($version) {
	global $_W;
	if (empty($version)) {
		return array();
	}
	$version_info = table('wxapp_versions')->getByUniacidAndVersion($_W['uniacid'], $version);
	if (empty($version_info['id'])) {
		return array();
	} else {
		return phoneapp_version($version_info['id']);
	}
}

function phoneapp_version($version_id) {
	$version_info = array();
	$version_id = intval($version_id);

	if (empty($version_id)) {
		return $version_info;
	}
	$version_info = table('wxapp_versions')->getById($version_id);
	$version_info = table('wxapp_versions')->dataunserializer($version_info);
	
	if (is_array($version_info['modules'])) {
		$uni_modules = uni_modules_by_uniacid($version_info['uniacid']);
		$uni_modules = array_keys($uni_modules);

		foreach ($version_info['modules'] as $i => $module) {
			if (!in_array($module['name'], $uni_modules)) {
				unset($version_info['modules'][$i]);
				continue;
			}
			$module_info = module_fetch($module['name']);
			$module_info['version'] = $module['version'];
			$module['uniacid'] = table('uni_link_uniacid')->getMainUniacid($version_info['uniacid'], $module['name'], $version_id);
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

function phoneapp_getpackage($data, $if_single = false) {
	load()->classs('cloudapi');
	$api = new CloudApi();
	$response = $api->post('phoneapp', 'download', $data, 'binary');
	if ($response['code'] == 200) {
		return error(0, $response['content']);
	}
	return error(1, $response['content']);
}



function phoneapp_version_all($uniacid) {
	load()->model('module');
	$phoneapp_versions = array();
	$uniacid = intval($uniacid);

	if (empty($uniacid)) {
		return $phoneapp_versions;
	}

	$phoneapp_versions = table('wxapp_versions')->getAllByUniacid($uniacid);
	if (!empty($phoneapp_versions)) {
		foreach ($phoneapp_versions as &$version) {
			$version = phoneapp_version($version['id']);
		}
	}

	return $phoneapp_versions;
}