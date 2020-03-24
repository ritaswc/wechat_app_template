<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('xzapp');
$account_info = permission_user_account_num();

$do = safe_gpc_belong($do, array('create'), 'list');

if($do == 'create') {
	if (!user_is_founder($_W['uid']) && $account_info['xzapp_limit'] < 0) {
		itoast('创建熊掌号个数已满', url('account/display', array('type' => XZAPP_TYPE_SIGN)));
	}
	if(checksubmit()) {
		$data = array(
			'name' => safe_gpc_string($_GPC['name']),
			'description' => safe_gpc_string($_GPC['description'])
		);

		$account_table = table('account');
		$account_table->searchWithTitle($data['name']);
		$account_table->searchWithType(ACCOUNT_TYPE_XZAPP_NORMAL);
		$exists = $account_table->searchAccountList();

		if (!empty($exists)) {
			itoast('该熊掌号名称已经存在！', url('account/display', array('type' => XZAPP_TYPE_SIGN)), 'error');
		}

		$uniacid = xzapp_create($data, $_W['uid']);
		if ($uniacid) {
			itoast('创建成功', url('account/display', array('type' => XZAPP_TYPE_SIGN)));
		} else {
			itoast('创建失败', '', 'error');
		}
	}
	template('xzapp/create');
}
