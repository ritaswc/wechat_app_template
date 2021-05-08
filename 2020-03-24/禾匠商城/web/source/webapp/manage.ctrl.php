<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('permission');
$account_info = permission_user_account_num();

$do = safe_gpc_belong($do, array('create', 'list', 'create_display'), 'list');
if($do == 'create') {
	if(!checksubmit()) {
		echo '非法提交';
		return;
	}
	if (!permission_user_account_creatable($_W['uid'], WEBAPP_TYPE_SIGN)) {
		itoast('创建PC个数已满', url('account/display', array('type' => WEBAPP_TYPE_SIGN)));
	}
	$data = array(
		'name' => safe_gpc_string($_GPC['name']),
		'description' => safe_gpc_string($_GPC['description'])
	);

	$account_table = table('account');
	$account_table->searchWithTitle($data['name']);
	$account_table->searchWithType(ACCOUNT_TYPE_WEBAPP_NORMAL);
	$exists = $account_table->searchAccountList();

	if (!empty($exists)) {
		itoast('该PC名称已经存在！', url('account/display', array('type' => XZAPP_TYPE_SIGN)), 'error');
	}

	$webapp = table('webapp');
	$uniacid = $webapp->createWebappInfo($data, $_W['uid']);
	if($uniacid){
		itoast('创建成功', url('account/display', array('type' => WEBAPP_TYPE_SIGN)));
	}
}

if($do == 'create_display') {
	if(!permission_user_account_creatable($_W['uid'], WEBAPP_TYPE_SIGN)) {
		itoast('创建PC个数已满', url('account/display', array('type' => WEBAPP_TYPE_SIGN)));
	}
	template('webapp/create');
}
