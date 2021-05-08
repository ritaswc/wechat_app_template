<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('cloud');
load()->func('communication');
load()->func('db');
load()->model('system');

$cloud_ready = cloud_prepare();
if (is_error($cloud_ready)) {
	message($cloud_ready['message'], url('cloud/diagnose'), 'error');
}

$dos = array('upgrade', 'get_upgrade_info', 'get_error_file_list');
$do = in_array($do, $dos) ? $do : 'upgrade';

if ('upgrade' == $do) {
	if (empty($_W['setting']['cloudip']) || $_W['setting']['cloudip']['expire'] < TIMESTAMP) {
	//	$cloudip = gethostbyname('api-upgrade.qiuma.wang');
	$cloudip = '127.0.0.1';
		if (empty($cloudip) || !preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $cloudip)) {
			itoast('云服务域名解析失败，请查看服务器DNS设置或是在“云服务诊断”中手动设置云服务IP', url('cloud/diagnose'), 'error');
		}
		setting_save(array('ip' => $cloudip, 'expire' => TIMESTAMP + 3600), 'cloudip');
	}

	$path = IA_ROOT . '/data/patch/' . date('Ymd') . '/';
	if (is_dir($path)) {
		if ($handle = opendir($path)) {
			while (false !== ($patchpath = readdir($handle))) {
				if ('.' != $patchpath && '..' != $patchpath) {
					if (is_dir($path . $patchpath)) {
						$patchs[] = $patchpath;
					}
				}
			}
		}
		if (!empty($patchs)) {
			sort($patchs, SORT_NUMERIC);
		}
	}
		$scrap_file = system_scrap_file();
	$have_no_permission_file = array();
	foreach ($scrap_file as $key => $file) {
		if (!file_exists(IA_ROOT . $file)) {
			continue;
		}
		$result = @unlink(IA_ROOT . $file);
		if (!$result) {
			$have_no_permission_file[] = $file;
		}
	}
	if ($have_no_permission_file) {
		itoast(implode('<br>', $have_no_permission_file) . '<br>以上废弃文件删除失败，可尝试将文件权限设置为777，再行删除！', referer(), 'error');
	}
}
if ('get_error_file_list' == $do) {
	$error_file_list = array();
	cloud_file_permission_pass($error_file_list);
	iajax(0, !empty($error_file_list) ? $error_file_list : '');
}
if ('get_upgrade_info' == $do) {
	$upgrade = cloud_build(true);
	if (is_error($upgrade)) {
		iajax(-1, $upgrade['message']);
	}

	if (!$upgrade['upgrade']) {
		cache_delete(cache_system_key('checkupgrade'));
		cache_delete(cache_system_key('cloud_transtoken'));
		iajax(1, '检查结果: 恭喜, 你的程序已经是最新版本. ');
	}
	if (!empty($upgrade['schemas'])) {
		$upgrade['database'] = cloud_build_schemas($upgrade['schemas']);
	}
	if (!empty($upgrade['files'])) {
		foreach ($upgrade['files'] as &$file) {
			if (is_file(IA_ROOT . $file)) {
				$file = 'M ' . $file;
			} else {
				$file = 'A ' . $file;
			}
		}
		unset($value);
	}
	iajax(0, $upgrade);
}
template('cloud/upgrade');