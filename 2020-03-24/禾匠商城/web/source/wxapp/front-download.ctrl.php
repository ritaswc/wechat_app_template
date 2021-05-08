<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('miniapp');
load()->classs('cloudapi');
load()->classs('uploadedfile');

$dos = array('front_download', 'domainset', 'code_uuid', 'code_gen', 'code_token', 'qrcode', 'checkscan',
	'commitcode', 'preview', 'getpackage', 'entrychoose', 'set_wxapp_entry',
	'custom', 'custom_save', 'custom_default', 'custom_convert_img', 'upgrade_module', 'tominiprogram');
$do = in_array($do, $dos) ? $do : 'front_download';

$wxapp_info = miniapp_fetch($_W['uniacid']);

$is_module_wxapp = false;
if (!empty($version_id)) {
	$is_single_module_wxapp = WXAPP_CREATE_MODULE == $version_info['type']; }


		if ('entrychoose' == $do) {
		$entrys = $version_info['cover_entrys'];
		template('wxapp/version-front-download');
	}
		if ('set_wxapp_entry' == $do) {
		$entry_id = intval($_GPC['entry_id']);
		$result = miniapp_update_entry($version_id, $entry_id);
		iajax(0, '设置入口成功');
	}


if ('custom' == $do) {
	$default_appjson = miniapp_code_current_appjson($version_id);

	$default_appjson = json_encode($default_appjson);
	template('wxapp/version-front-download');
}
if ('custom_default' == $do) {
	$result = miniapp_code_set_default_appjson($version_id);
	if (false === $result) {
		iajax(1, '操作失败，请重试！');
	} else {
		iajax(0, '设置成功！', url('wxapp/front-download/front_download', array('version_id' => $version_id)));
	}
}

if ('custom_save' == $do) {
	if (empty($version_info)) {
		iajax(1, '参数错误！');
	}
	$json = array();
	if (!empty($_GPC['json']['window'])) {
		$json['window'] = array(
			'navigationBarTitleText' => safe_gpc_string($_GPC['json']['window']['navigationBarTitleText']),
			'navigationBarTextStyle' => safe_gpc_string($_GPC['json']['window']['navigationBarTextStyle']),
			'navigationBarBackgroundColor' => safe_gpc_string($_GPC['json']['window']['navigationBarBackgroundColor']),
			'backgroundColor' => safe_gpc_string($_GPC['json']['window']['backgroundColor']),
		);
	}
	if (!empty($_GPC['json']['tabBar'])) {
		$json['tabBar'] = array(
			'color' => safe_gpc_string($_GPC['json']['tabBar']['color']),
			'selectedColor' => safe_gpc_string($_GPC['json']['tabBar']['selectedColor']),
			'backgroundColor' => safe_gpc_string($_GPC['json']['tabBar']['backgroundColor']),
			'borderStyle' => in_array($_GPC['json']['tabBar']['borderStyle'], array('black', 'white')) ? $_GPC['json']['tabBar']['borderStyle'] : '',
		);
	}
	$result = miniapp_code_save_appjson($version_id, $json);
	cache_delete(cache_system_key('miniapp_version', array('version_id' => $version_id)));
	iajax(0, '设置成功！', url('wxapp/front-download/front_download', array('version_id' => $version_id)));
}

if ('custom_convert_img' == $do) {
	$attchid = intval($_GPC['att_id']);
	$filename = miniapp_code_path_convert($attchid);
	iajax(0, $filename);
}

if ('front_download' == $do) {
	permission_check_account_user('wxapp_profile_front_download');
	$appurl = $_W['siteroot'] . '/app/index.php';
	$uptype = $_GPC['uptype'];
	$wxapp_versions_info = miniapp_version($version_id);
	if (!in_array($uptype, array('auto', 'normal'))) {
		$uptype = 'auto';
	}
	if (!empty($wxapp_versions_info['last_modules'])) {
		$last_modules = current($wxapp_versions_info['last_modules']);
	}
	$need_upload = false;
	$module = array();
	if (!empty($wxapp_versions_info['modules'])) {
		foreach ($wxapp_versions_info['modules'] as $item) {
			$module = module_fetch($item['name']);
			$need_upload = !empty($last_modules) && ($module['version'] != $last_modules['version']);
		}
	}
	if (!empty($wxapp_versions_info['version'])) {
		$user_version = explode('.', $wxapp_versions_info['version']);
		$user_version[count($user_version) - 1] += 1;
		$user_version = join('.', $user_version);
	}
	template('wxapp/version-front-download');
}

if ('upgrade_module' == $do) {
	$modules = table('wxapp_versions')
		->where(array('id' => $version_id))
		->getcolumn('modules');
	$modules = iunserializer($modules);
	if (!empty($modules)) {
		foreach ($modules as $name => $module) {
			$module_info = module_fetch($name);
			if (!empty($module_info['version'])) {
				$modules[$name]['version'] = $module_info['version'];
			}
		}
		$modules = iserializer($modules);
		table('wxapp_versions')
			->where(array('id' => $version_id))
			->fill(array(
				'modules' => $modules,
				'last_modules' => $modules,
				'version' => $_GPC['version'],
				'description' => trim($_GPC['description']),
				'upload_time' => TIMESTAMP,
			))
			->save();
		cache_delete(cache_system_key('miniapp_version', array('version_id' => $version_id)));
	}
	exit;
}

if ('code_uuid' == $do) {
	$user_version = $_GPC['user_version'];
	$data = miniapp_code_generate($version_id, $user_version);
	echo json_encode($data);
}

if ('code_gen' == $do) {
	$code_uuid = $_GPC['code_uuid'];
	$data = miniapp_check_code_isgen($code_uuid);
	echo json_encode($data);
}

if ('code_token' == $do) {
	$tokendata = miniapp_code_token();
	echo json_encode($tokendata);
}

if ('qrcode' == $do) {
	$code_token = $_GPC['code_token'];
	header('Content-type: image/jpg'); 	echo miniapp_code_qrcode($code_token);
	exit;
}

if ('checkscan' == $do) {
	$code_token = $_GPC['code_token'];
	$last = $_GPC['last'];
	$data = miniapp_code_check_scan($code_token, $last);
	echo json_encode($data);
}

if ('preview' == $do) {
	$code_token = $_GPC['code_token'];
	$code_uuid = $_GPC['code_uuid'];
	$data = miniapp_code_preview_qrcode($code_uuid, $code_token);
	echo json_encode($data);
}

if ('commitcode' == $do) {
	$user_version = $_GPC['user_version'];
	$user_desc = $_GPC['user_desc'];
	$code_token = $_GPC['code_token'];
	$code_uuid = $_GPC['code_uuid'];
	$data = miniapp_code_commit($code_uuid, $code_token, $user_version, $user_desc);
	echo json_encode($data);
}

if ('tominiprogram' == $do) {
	$tomini_lists = iunserializer($version_info['tominiprogram']);
	if (!is_array($tomini_lists)) {
		$tomini_lists = array();
		miniapp_version_update($version_id, array('tominiprogram' => iserializer(array())));
	}

	if (checksubmit()) {
		$appids = $_GPC['appid'];
		$app_names = $_GPC['app_name'];
		$is_add = intval($_GPC['is_add']);

		if (!is_array($appids) || !is_array($app_names)) {
			itoast('参数有误！', referer(), 'error');
		}
		$data = $is_add ? $tomini_lists : array();
		foreach ($appids as $k => $appid) {
			if (empty($appid) || empty($app_names[$k])) {
				continue;
			}
			$appid = safe_gpc_string($appid);
			$data[$appid] = array(
				'appid' => $appid,
				'app_name' => safe_gpc_string($app_names[$k])
			);
			if (count($data) >= 10) {
				break;
			}
		}
		miniapp_version_update($version_id, array('tominiprogram' => iserializer($data)));
		itoast('保存成功！', referer(), 'success');
	}
	template('wxapp/version-front-download');
}

if ('getpackage' == $do) {
	if (empty($version_id)) {
		itoast('参数错误！', '', '');
	}
	$account_wxapp_info = miniapp_fetch($version_info['uniacid'], $version_id);
	if (empty($account_wxapp_info)) {
		itoast('版本不存在！', referer(), 'error');
	}

	$siteurl = $_W['siteroot'] . 'app/index.php';
	if (!empty($account_wxapp_info['appdomain'])) {
		$siteurl = $account_wxapp_info['appdomain'];
	}

	$request_cloud_data = array(
		'name' => $account_wxapp_info['name'],
		'modules' => $account_wxapp_info['version']['modules'],
		'support' => $_W['account']['type_sign'], 		'siteInfo' => array(
			'name' => $account_wxapp_info['name'],
			'uniacid' => $account_wxapp_info['uniacid'],
			'acid' => $account_wxapp_info['acid'],
			'multiid' => $account_wxapp_info['version']['multiid'],
			'version' => $account_wxapp_info['version']['version'],
			'siteroot' => $siteurl,
			'design_method' => $account_wxapp_info['version']['design_method'],
		),
			);
	$result = miniapp_getpackage($request_cloud_data);

	if (is_error($result)) {
		itoast($result['message'], '', '');
	} else {
		header('content-type: application/zip');
		header('content-disposition: attachment; filename="' . $request_cloud_data['name'] . '.zip"');
		echo $result;
	}
	exit;
}
