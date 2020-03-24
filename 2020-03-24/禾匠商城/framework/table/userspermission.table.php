<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

class UserspermissionTable extends We7Table {
	protected $tableName = 'users_permission';
	protected $primaryKey = 'id';
	protected $field = array('id', 'uniacid', 'uid', 'type', 'permission', 'url');

	public function userPermissionInfo($uid, $uniacid, $type = '') {
		$condition = array('uid' => $uid, 'uniacid' => $uniacid);
		if (!empty($type)) {
			$condition['type'] = $type;
		}
		return $this->query->from('users_permission')->where($condition)->get();
	}

	public function userModulesPermission($uid, $uniacid) {
		$condition = array(
			'uid'=> $uid,
			'uniacid' => $uniacid,
			'type !=' => array(PERMISSION_ACCOUNT, PERMISSION_WXAPP),
		);
		return $this->query->from('users_permission')->where($condition)->getall('type');
	}

	public function userPermission($uid, $uniacid) {
		return $this->query->from('users_permission')->where('uid', $uid)->where('uniacid', $uniacid)->getall('type');
	}

	public function moduleClerkPermission($module) {
		global $_W;
		return $this->query->from('users_permission', 'p')->leftjoin('uni_account_users', 'u')->on(array('u.uid' => 'p.uid', 'u.uniacid' => 'p.uniacid'))->where('u.role', 'clerk')->where('p.type', $module)->where('u.uniacid', $_W['uniacid'])->getall('uid');
	}
}