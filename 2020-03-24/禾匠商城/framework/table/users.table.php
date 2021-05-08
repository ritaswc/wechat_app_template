<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

class UsersTable extends We7Table {
	protected $tableName = 'users';
	protected $field = array('uid');

	public function searchUsersList() {
		global $_W;
		$this->query->from('users', 'u')
				->select('u.*, p.avatar as avatar, p.mobile as mobile, p.uid as puid, p.mobile as mobile')
				->leftjoin('users_profile', 'p')
				->on(array('u.uid' => 'p.uid'))
				->orderby('u.uid', 'DESC');
		if (user_is_vice_founder()) {
			$this->query->where('u.owner_uid', $_W['uid']);
		}
		return $this->query->getall('uid');
	}

	
	public function userOwnedAccount($uid) {
		$uniacid_list = $this->query->from('uni_account_users')->where('uid', $uid)->getall('uniacid');
		return array_keys($uniacid_list);
	}

	public function userOwnedAccountRole($uid, $uniacid = 0) {
		if (empty($uniacid)) {
			$role = $this->query->from('uni_account_users')->where('uid', $uid)->getall('role');
			return array_keys($role);
		} else {
			$role = $this->query->from('uni_account_users')->where(array('uid' => $uid, 'uniacid' => $uniacid))->get();
			return $role['role'];
		}
	}


	public function searchWithStatus($status) {
		$this->query->where('u.status', $status);
		return $this;
	}

	public function searchWithType($type) {
		$this->query->where('u.type', $type);
		return $this;
	}

	public function searchWithFounder($founder_groupids) {
		$this->query->where('u.founder_groupid', $founder_groupids);
		return $this;
	}

	public function searchWithTimelimitStatus($status) {
		if ($status == 1) {
						$this->where(function ($query) {
				$query->where('u.endtime', 0)->whereor('u.endtime >', TIMESTAMP);
			});
		} elseif ($status == 2) {
						$this->where('u.endtime !=', 0)->where('u.endtime <=', TIMESTAMP);
		}
		return $this;
	}

	public function searchWithEndtime($day) {
		$this->query->where('u.endtime !=', 0)->where('u.endtime <', TIMESTAMP + 86400 * $day);
		return $this;
	}

	public function searchWithMobile() {
		$this->query->where('p.mobile !=', '');;
		return $this;
	}

	public function searchWithGroupId($group_id) {
		$this->query->where('u.groupid', $group_id);
		return $this;
	}

	public function searchWithSendStatus() {
		$this->query->where('p.send_expire_status', 0);;
		return $this;
	}

	public function searchWithNameOrMobile($search) {
		$this->query->where('u.username LIKE', "%{$search}%")->whereor('p.mobile LIKE', "%{$search}%");
		return $this;
	}

	public function searchWithOwnerUid($owner_uid) {
		$this->query->where('u.owner_uid', $owner_uid);
		return $this;
	}

	public function accountUsersNum($uid) {
		return $this->query->from('uni_account_users')->where('uid', $uid)->count();
	}

	public function usersGroup() {
		return $this->query->from('users_group')->getall('id');
	}

	public function usersGroupInfo($groupid) {
		return $this->query->from('users_group')->where('id', $groupid)->get();
	}

	public function usersInfo($uid) {
		return $this->query->from('users')->where('uid', $uid)->get();
	}

	public function usersFounderGroup() {
		return $this->query->from('users_founder_group')->getall('id');
	}

	public function userFounderGroupInfo($groupid) {
		return $this->query->from('users_founder_group')->where('id', $groupid)->get();
	}

	public function userProfileMobile($mobile) {
		return $this->query->from('users_profile')->where('mobile', $mobile)->get();
	}

	public function userVerifyCode($receiver, $verifycode) {
		return $this->query->from('uni_verifycode')->where('receiver', $receiver)->where('verifycode', $verifycode)->where('uniacid', 0)->get();
	}

	public function userBindInfo($bind_sign, $third_type) {
		return $this->query->from('users_bind')->where('bind_sign', $bind_sign)->where('third_type', $third_type)->get();
	}

	public function userProfileFields() {
		return $this->query->from('profile_fields')->where('available', 1)->where('showinregister', 1)->orderby('displayorder', 'desc')->getall('field');
	}

	public function userBind() {
		return $this->query->from('users_bind')->getall('bind_sign');
	}

	public function bindSearchWithUser($uid) {
		$this->query->where('uid', $uid);
		return $this;
	}

	public function bindSearchWithType($type) {
		$this->query->where('third_type', $type);
		return $this;
	}

	public function bindInfo() {
		return $this->query->from('users_bind')->get();
	}
	public function userProfile($uid) {
		return $this->query->from('users_profile')->where('uid', $uid)->get();
	}

	public function userAccountRole($role) {
		$this->query->where('role', $role);
		return $this;
	}

	public function userOrderBy($field = 'uid', $order = 'desc') {
		$field = !empty($field) ? $field : 'joindate';
		$order = !empty($order) ? $order : 'desc';
		$this->query->orderby($field, $order);
		return $this;
	}

	public function userAccountDelete($uid, $is_recycle = false) {
		if (!empty($is_recycle)) {
			pdo_update('users', array('status' => USER_STATUS_BAN) , array('uid' => $uid));
			return true;
		}
		$user_info = $this->usersInfo($uid);
		if ($user_info['founder_groupid'] == ACCOUNT_MANAGE_GROUP_VICE_FOUNDER) {
			pdo_update('users', array('owner_uid' => ACCOUNT_NO_OWNER_UID), array('owner_uid' => $uid));
			pdo_update('users_group', array('owner_uid' => ACCOUNT_NO_OWNER_UID), array('owner_uid' => $uid));
			pdo_update('uni_group', array('owner_uid' => ACCOUNT_NO_OWNER_UID), array('owner_uid' => $uid));
		}
		pdo_delete('users', array('uid' => $uid));
		pdo_delete('uni_account_users', array('uid' => $uid));
		pdo_delete('users_profile', array('uid' => $uid));
		pdo_delete('users_bind', array('uid' => $uid));
		return true;
	}
}