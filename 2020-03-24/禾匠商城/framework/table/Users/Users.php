<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class Users extends \We7Table {
	protected $tableName = 'users';
	protected $primaryKey = 'uid';
	protected $field = array(
		'owner_uid',
		'groupid',
		'username',
		'password',
		'salt',
		'type',
		'status',
		'joindate',
		'joinip',
		'lastvisit',
		'lastip',
		'remark',
		'starttime',
		'endtime',
		'founder_groupid',
		'register_type',
		'openid',
		'welcome_link',
		'notice_setting',
	);
	protected $default = array(
		'owner_uid' => '0',
		'groupid' => '0',
		'username' => '',
		'password' => '',
		'salt' => '',
		'type' => '1',
		'status' => '2',
		'joindate' => '0',
		'joinip' => '',
		'lastvisit' => '0',
		'lastip' => '',
		'remark' => '',
		'starttime' => '0',
		'endtime' => '0',
		'founder_groupid' => '0',
		'register_type' => '0',
		'openid' => '0',
		'welcome_link' => '',
		'notice_setting' => '',
	);

	public function getNoticeSettingByUid($uid) {
		$notice_setting = iunserializer($this->where('uid', $uid)->getcolumn('notice_setting'));
		if (!is_array($notice_setting)) {
			$notice_setting = array();
		}
		return $notice_setting;
	}

	public function getUsersList($join_profile = true) {
		$this->query->from('users', 'u');

		$select = 'u.uid, u.owner_uid, u.groupid, u.username, u.type, u.status, u.joindate, u.joinip, u.lastvisit,
				u.lastip, u.remark, u.starttime, u.endtime, u.founder_groupid,u.register_type, u.openid, u.welcome_link';

		if ($join_profile) {
			$select .= ',p.avatar as avatar, p.mobile as mobile, p.uid as puid, p.mobile as mobile';
			$this->query->leftjoin('users_profile', 'p')->on(array('u.uid' => 'p.uid'));
		}
		return $this->query->select($select)->orderby('u.uid', 'DESC')->getall('uid');
	}

	public function searchFounderOwnUsers($founder_uid) {
		$this->query
			->innerjoin('users_founder_own_users', 'f')
			->on(array('u.uid' => 'f.uid'))
			->where('f.founder_uid', $founder_uid);
		return $this;
	}

	public function searchWithViceFounder() {
		global $_W;
		if (user_is_vice_founder()) {
			$this->query->where('u.owner_uid', $_W['uid']);
		}
		return $this;
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

	public function searchWithoutFounder()
	{
		return $this->query->where('u.founder_groupid !=', ACCOUNT_MANAGE_GROUP_FOUNDER);
	}

	public function searchWithTimelimitStatus($status) {
		if ($status == 1) {
						$this->where(function ($query) {
				$query->where('u.endtime', 0)
					->whereor('u.endtime', USER_ENDTIME_GROUP_UNLIMIT_TYPE)
					->whereor('u.endtime >', TIMESTAMP);
			});
		} elseif ($status == 2) {
						$this->where(function ($query) {
				$query->where('u.endtime >', USER_ENDTIME_GROUP_UNLIMIT_TYPE)
					->where('u.endtime <=', TIMESTAMP)
					->whereor('u.endtime', USER_ENDTIME_GROUP_DELETE_TYPE);
			});

		}
		return $this;
	}

	public function searchWithEndtime($day) {
		$this->query->where('u.endtime >', USER_ENDTIME_GROUP_UNLIMIT_TYPE)->where('u.endtime <', TIMESTAMP + 86400 * $day);
		return $this;
	}

	public function searchWithMobile() {
		$this->query->where('p.mobile !=', '');
		return $this;
	}

	public function searchWithGroupId($group_id) {
		$this->query->where('u.groupid', $group_id);
		return $this;
	}

	public function searchWithSendStatus() {
		$this->query->where('p.send_expire_status', 0);
		return $this;
	}

	public function searchWithNameOrMobile($search, $join_profile = true, $uid_in = array()) {
		if ($join_profile) {
			$this->query->where(function ($query) use ($search) {
				$query->where('u.username LIKE', "%{$search}%")->whereor('p.mobile LIKE', "%{$search}%");
			});
		} else {
			$this->query->where('u.username LIKE', "%{$search}%")->whereor('u.uid', $uid_in);
		}
		return $this;
	}

	public function searchWithOwnerUid($owner_uid) {
		$this->query->where('u.owner_uid', $owner_uid);
		return $this;
	}

	public function userOrderBy($field = 'uid', $order = 'desc') {
		$field = !empty($field) ? $field : 'joindate';
		$order = !empty($order) ? $order : 'desc';
		$this->query->orderby($field, $order);
		return $this;
	}
}