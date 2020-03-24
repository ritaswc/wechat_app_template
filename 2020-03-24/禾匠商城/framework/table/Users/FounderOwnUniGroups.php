<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class FounderOwnUniGroups extends \We7Table {
	protected $tableName = 'users_founder_own_uni_groups';
	protected $primaryKey = 'id';
	protected $field = array(
		'founder_uid',
		'uni_group_id',

	);
	protected $default = array(
		'founder_uid' => '',
		'uni_group_id' => '',

	);

	public function addOwnUniGroup($founder_uid, $uni_group_id) {
		$fill = array(
			'founder_uid' => $founder_uid,
			'uni_group_id' => $uni_group_id,
		);
		return $this->fill($fill)->save();
	}

	public function getOwnUniGroupsByFounderUid($founder_uid) {
		return $this->where('founder_uid', $founder_uid)
			->getall('uni_group_id');
	}

	public function getByFounderUidAndUniGroupId($founder_uid, $uni_group_id) {
		return $this->where('founder_uid', $founder_uid)->where('uni_group_id', $uni_group_id)->get();
	}
}