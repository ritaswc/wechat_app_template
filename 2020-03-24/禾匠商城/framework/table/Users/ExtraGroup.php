<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class ExtraGroup extends \We7Table {
	protected $tableName = 'users_extra_group';
	protected $primaryKey = 'id';
	protected $field = array(
		'uid',
		'uni_group_id',
		'create_group_id',

	);
	protected $default = array(
		'uid' => '',
		'uni_group_id' => '0',
		'create_group_id' => '0',

	);

	public function searchWithUidCreateGroupId($uid, $create_group_id) {
		return $this->where('uid', $uid)->where('create_group_id', $create_group_id);
	}

	public function getUniGroupByUidAndGroupid($uid, $uni_group_id) {
		$where = array('uid' => $uid, 'uni_group_id' => $uni_group_id);
		return $this->where($where)->get();
	}

	public function addExtraUniGroup($uid, $uni_group_id) {
		$data = array('uid' => $uid, 'uni_group_id' => $uni_group_id);
		return $this->fill($data)->save();
	}


	public function getCreateGroupByUidAndGroupid($uid, $create_group_id) {
		$where = array('uid' => $uid, 'create_group_id' => $create_group_id);
		return $this->where($where)->get();
	}

	public function addExtraCreateGroup($uid, $create_group_id) {
		$data = array('uid' => $uid, 'create_group_id' => $create_group_id);
		return $this->fill($data)->save();
	}

	public function getCreateGroupsByUid($uid) {
		$where = array('uid' => $uid, 'create_group_id !=' => 0);
		return $this->where($where)->getall('create_group_id');
	}

	public function getUniGroupsByUid($uid) {
		$where = array('uid' => $uid, 'uni_group_id !=' => 0);
		return $this->where($where)->getall('uni_group_id');
	}

}