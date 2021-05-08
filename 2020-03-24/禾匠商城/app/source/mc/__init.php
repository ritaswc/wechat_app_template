<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
if ($action != 'cash') {
	checkauth();
}
if ($controller == 'mc' && $action == 'card') {
	if ($do == 'sign_display') {
		header('Location: ' . murl('entry', array('m' => 'we7_coupon', 'do' => 'card', 'op' => 'sign_display')));
		exit;
	} elseif ($do == 'notice') {
		header('Location: ' . murl('entry', array('m' => 'we7_coupon', 'do' => 'card', 'op' => 'notice')));
		exit;
	} else {
		header('Location: ' . murl('entry', array('m' => 'we7_coupon', 'do' => 'card')));
		exit;
	}
}
$filter = array();
$setting = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors', 'payment', 'passport'));
$behavior = $setting['creditbehaviors'];
$creditnames = $setting['creditnames'];
$credits = mc_credit_fetch($_W['member']['uid'], '*');

$ucpage = table('site_page')
	->where(array(
		'uniacid' => $_W['uniacid'],
		'type' => 3,
	))->get();
if (!empty($ucpage['params'])) {
	$ucpage['params'] = json_decode($ucpage['params'], true);
}
$title = $ucpage['title'];