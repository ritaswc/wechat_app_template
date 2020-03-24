<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('module');
if (!empty($_W['uid'])) {
	header('Location: ' . url('account/display', array('type' => ACCOUNT_TYPE_SIGN)));
	exit;
}


$settings = $_W['setting'];

	if (!empty($settings['site_welcome_module'])) {
		$site = WeUtility::createModuleSystemWelcome($settings['site_welcome_module']);
		if (!is_error($site)) {
			exit($site->systemWelcomeDisplay());
		}
	}

$copyright = $settings['copyright'];
$copyright['slides'] = iunserializer($copyright['slides']);
if (isset($copyright['showhomepage']) && empty($copyright['showhomepage'])) {
	header('Location: ' . url('user/login'));
	exit;
}
load()->model('article');
$notices = article_notice_home();
$news = article_news_home();
template('account/welcome');
