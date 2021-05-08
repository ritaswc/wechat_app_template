<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class FounderOwnUsers extends \We7Table {
	protected $tableName = 'users_founder_own_users';
	protected $primaryKey = 'id';
	protected $field = array(
		'uid',
		'founder_uid',

	);
	protected $default = array(
		'uid' => '',
		'founder_uid' => '',

	);

	public function addOwnUser($uid, $founder_uid) {
		$fill = array(
			'uid' => $uid,
			'founder_uid' => $founder_uid,
		);
		return $this->fill($fill)->save();
	}

	public function updateOwnUser($uid, $founder_uid) {
		return $this->where('uid', $uid)->fill(array(
			'uid' => $uid,
			'founder_uid' => $founder_uid,
		))->save();
	}

	public function getFounderByUid($uid) {
		return $this->where('uid', $uid)->get();
	}

	public function getFounderOwnUsersList($founder_uid) {
		return $this->where('founder_uid', $founder_uid)->getall('uid');
	}


}