<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('system');

$dos = array('display', 'get_attach_size');
$do = in_array($do, $dos) ? $do : 'display';

if ('display' == $do) {
	$info = array(
		'os' => php_uname(),
		'php' => PHP_VERSION,
		'sapi' => $_SERVER['SERVER_SOFTWARE'] ? $_SERVER['SERVER_SOFTWARE'] : php_sapi_name(),
	);
	$info['family'] = '您的产品是';
	switch(IMS_FAMILY) {
		case 'v':
			$info['family'] .= '开源版, 没有购买商业授权, 不能用于商业用途';
			break;
		case 's':
			$info['family'] .= '授权版';
			break;
		case 'x':
			$info['family'] .= '商业版';
			break;
		default:
			$info['family'] .= '单版';
	}

		$size = 0;
	$size = @ini_get('upload_max_filesize');
	if ($size) {
		$size = bytecount($size);
	}
	if ($size > 0) {
		$ts = @ini_get('post_max_size');
		if ($ts) {
			$ts = bytecount($size);
		}
		if ($ts > 0) {
			$size = min($size, $ts);
		}
		$ts = @ini_get('memory_limit');
		if ($ts) {
			$ts = bytecount($size);
		}
		if ($ts > 0) {
			$size = min($size, $ts);
		}
	}
	if (empty($size)) {
		$size = '';
	} else {
		$size = sizecount($size);
	}
	$info['limit'] = $size;

		$sql = 'SELECT VERSION();';
	$info['mysql']['version'] = pdo_fetchcolumn($sql);

		$tables = pdo_fetchall("SHOW TABLE STATUS LIKE '" . $_W['config']['db']['tablepre'] . "%'");
	$size = 0;
	foreach ($tables as &$table) {
		$size += $table['Data_length'] + $table['Index_length'];
	}
	if (empty($size)) {
		$size = '';
	} else {
		$size = sizecount($size);
	}
		$info['mysql']['size'] = $size;
		$info['attach']['url'] = $_W['attachurl'];

	if (empty($_W['setting']['remote_complete_info']['type'])) {
		$info['attach']['url'] = $_W['siteroot'] . $_W['config']['upload']['attachdir'] . '/';
	}
	$info['company'] = '宿 州 市 微 擎 云 计 算 有 限 公 司';
	$info['developers'] = array('袁文涛', '任超 (米粥)', '马德坤', '宋建君 (Gorden)', '赵波', '杨峰', '卜睿君', '张宏', '高建业', '葛海波', '马莉娜', '樊永康', '王玉', '翟佳佳', '张拯', '张玮');
	$info['operators'] = array('侯琪琪 (琪琪)', '杨欣雨 (小雨)', '赵小雷 (擎擎)', '蔡帅帅 (小帅)', '朱传宝 (阿宝)', '蒋康康 (阿康)', '王鹏 (鹏鹏)');
	$info['exchange_group'] = array('link' => 'https://jq.qq.com/?_wv=1027&k=5NzXzQ3', 'title' => '547025486');
	template('system/systeminfo');
}

if ('get_attach_size' == $do) {
		$path = IA_ROOT . '/' . $_W['config']['upload']['attachdir'];
	$size = dir_size($path);
	if (empty($size)) {
		$size = '';
	} else {
		$size = sizecount($size);
	}
	iajax(0, array('attach_size' => $size));
}