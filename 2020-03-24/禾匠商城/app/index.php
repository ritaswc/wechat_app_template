<?php
/**
 * [WeEngine System] Copyright (c) 20191214152809 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);

require '../framework/bootstrap.inc.php';
require IA_ROOT . '/app/common/bootstrap.app.inc.php';

$acl = array(
	'home' => array(
		'default' => 'home',
	),
	'mc' => array(
		'default' => 'home'
	)
);

if ($_W['setting']['copyright']['status'] == 1) {
	$_W['siteclose'] = true;
	message('抱歉，站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason']);
}
$multiid = intval($_GPC['t']);
if(empty($multiid)) {
		$multiid = intval($unisetting['default_site']);
	unset($setting);
}

$multi = table('site_multi')->getById($multiid, $_W['uniacid']);

$multi['site_info'] = @iunserializer($multi['site_info']);

$styleid = !empty($_GPC['s']) ? intval($_GPC['s']) : intval($multi['styleid']);
$style = table('site_styles')->getById($styleid, $_W['uniacid']);

$templates = uni_templates();
$templateid = intval($style['templateid']);
$template = $templates[$templateid];

$_W['template'] = !empty($template) ? $template['name'] : 'default';
$_W['styles'] = array();

if(!empty($template) && !empty($style)) {
	$stylevars = table('site_styles_vars')
		->select(array('variable', 'content'))
		->where(array(
			'uniacid' => $_W['uniacid'],
			'styleid' => $styleid
		))
		->getall();
	if(!empty($stylevars)) {
		foreach($stylevars as $row) {
			if (strexists($row['variable'], 'img')) {
				$row['content'] = tomedia($row['content']);
			}
			$_W['styles'][$row['variable']] = $row['content'];
		}
	}
	unset($stylevars, $row, $sql, $params);
}

$_W['page'] = array();
$_W['page']['title'] = $multi['title'];
if(is_array($multi['site_info'])) {
	$_W['page'] = array_merge($_W['page'], $multi['site_info']);
}
unset($multi, $styleid, $style, $templateid, $template, $templates);

if ($controller == 'wechat' && $action == 'card' && $do == 'use') {
	header("location: index.php?i={$_W['uniacid']}&c=entry&m=paycenter&do=consume&encrypt_code={$_GPC['encrypt_code']}&card_id={$_GPC['card_id']}&openid={$_GPC['openid']}&source={$_GPC['source']}");
	exit;
}
$controllers = array();
$handle = opendir(IA_ROOT . '/app/source/');
if(!empty($handle)) {
	while($dir = readdir($handle)) {
		if($dir != '.' && $dir != '..') {
			$controllers[] = $dir;
		}
	}
}
if(!in_array($controller, $controllers)) {
	$controller = 'home';
}
$init = IA_ROOT . "/app/source/{$controller}/__init.php";
if(is_file($init)) {
	require $init;
}

$actions = array();
$handle = opendir(IA_ROOT . '/app/source/' . $controller);
if(!empty($handle)) {
	while($dir = readdir($handle)) {
		if($dir != '.' && $dir != '..' && strexists($dir, '.ctrl.php')) {
			$dir = str_replace('.ctrl.php', '', $dir);
			$actions[] = $dir;
		}
	}
}

if(empty($actions)) {
	$str = '';
	if(uni_is_multi_acid()) {
		$str = "&j={$_W['acid']}";
	}
	header("location: index.php?i={$_W['uniacid']}{$str}&c=home?refresh");
}
if(!in_array($action, $actions)) {
	$action = $acl[$controller]['default'];
}
if(!in_array($action, $actions)) {
	$action = $actions[0];
}
require _forward($controller, $action);

function _forward($c, $a) {
	$file = IA_ROOT . '/app/source/' . $c . '/' . $a . '.ctrl.php';
	return $file;
}