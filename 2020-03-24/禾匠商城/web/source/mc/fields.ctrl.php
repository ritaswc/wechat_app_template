<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

permission_check_account_user('mc_member_fields');

$dos = array('list', 'post', 'change_available');
$do = in_array($do, $dos) ? $do : 'list';
if ('list' == $do) {
	$account_member_fields = uni_account_member_fields($_W['uniacid']);
}

if ('change_available' == $do) {
	$id = intval($_GPC['id']);
	$available = intval($_GPC['available']);
	$res = table('mc_member_fields')
		->where(array('id' => $id))
		->fill(array('available' => $available = empty($available) ? 1 : 0))
		->save();
	if ($res) {
		iajax(0, '会员字段更新成功！');
	} else {
		iajax(0, '会员字段更新失败！');
	}
}

if ('post' == $do) {
	$id = intval($_GPC['id']);
	if (checksubmit('submit')) {
		if (empty($_GPC['title'])) {
			message('抱歉，请填写资料名称！');
		}
		$field = array(
			'title' => safe_gpc_string($_GPC['title']),
			'displayorder' => safe_gpc_int($_GPC['displayorder']),
			'available' => safe_gpc_int($_GPC['available']),
		);
		table('mc_member_fields')
			->where(array(
				'id' => $id,
				'uniacid' => $_W['uniacid']
			))
			->fill($field)
			->save();
		message('会员字段更新成功！', url('mc/fields'), 'success');
	}
	$field = table('mc_member_fields')->getById($id);
}
template('mc/fields');
