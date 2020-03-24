<?php
defined('IN_IA') or exit('Access Denied');


function xzapp_create($attr, $uid) {
	$name = $attr['name'];
	$description = $attr['description'];
	$data = array(
		'name' => $name,
		'description' => $description,
		'title_initial' => get_first_pinyin($name),
		'groupid' => 0,
	);

	if (!pdo_insert('uni_account', $data)) {
		return false;
	}
	$uniacid = pdo_insertid();
	if(!$uniacid) {
		return false;
	}
	$accountdata = array('uniacid' => $uniacid, 'type' => ACCOUNT_TYPE_XZAPP_NORMAL, 'hash' => random(8));
	pdo_insert('account', $accountdata);
	$acid = pdo_insertid();
	pdo_update('uni_account', array('default_acid'=>$acid), array('uniacid'=>$uniacid));
	pdo_insert('account_xzapp', array('uniacid'=>$uniacid, 'acid'=>$acid, 'name'=>$name));

	$unisettings['creditnames'] = array('credit1' => array('title' => '积分', 'enabled' => 1), 'credit2' => array('title' => '余额', 'enabled' => 1));
	$unisettings['creditnames'] = iserializer($unisettings['creditnames']);
	$unisettings['creditbehaviors'] = array('activity' => 'credit1', 'currency' => 'credit2');
	$unisettings['creditbehaviors'] = iserializer($unisettings['creditbehaviors']);
	$unisettings['uniacid'] = $uniacid;
	pdo_insert('uni_settings', $unisettings);
	return $uniacid;
}