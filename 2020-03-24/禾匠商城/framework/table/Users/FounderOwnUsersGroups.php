<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class FounderOwnUsersGroups extends \We7Table {
	protected $tableName = 'users_founder_own_users_groups';
	protected $primaryKey = 'id';
	protected $field = array(
		'founder_uid',
		'users_group_id',

	);
	protected $default = array(
		'founder_uid' => '',
		'users_group_id' => '',

	);

	public function addOwnUsersGroup($founder_uid, $users_group_id) {
		$fill = array(
			'founder_uid' => $founder_uid,
			'users_group_id' => $users_group_id,
		);
		return $this->fill($fill)->save();
	}

}