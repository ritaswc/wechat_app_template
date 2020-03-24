<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('site');
$multiid = intval($_GPC['t']);
if(empty($multiid)) {
	load()->model('account');
	$setting = uni_setting($_W['uniacid'], array('default_site'));
	$multiid = $setting['default_site'];
}
$title = $_W['page']['title'];
$navs = site_app_navs('home', $multiid);
$share_tmp = table('cover_reply')
	->where(array(
		'uniacid' => $_W['uniacid'],
		'multiid' => $multiid,
		'module' => 'site'
	))
	->select(array('title', 'description', 'thumb'))
	->get();
$_share['imgUrl'] = tomedia($share_tmp['thumb']);
$_share['desc'] = $share_tmp['description'];
$_share['title'] = $share_tmp['title'];
$category_list = table('site_category')
	->where(array(
		'uniacid' => $_W['uniacid'],
		'multiid' => $multiid
	))
	->getall('id');
if (!empty($multiid)) {
	isetcookie('__multiid', $multiid);
}
template('home/home');