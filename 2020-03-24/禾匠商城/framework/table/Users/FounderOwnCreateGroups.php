<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class FounderOwnCreateGroups extends \We7Table {
	protected $tableName = 'users_founder_own_create_groups';
	protected $primaryKey = 'id';
	protected $field = array(
		'founder_uid',
		'create_group_id',

	);
	protected $default = array(
		'founder_uid' => '',
		'create_group_id' => '',

	);

	public function addOwnCreateGroup($founder_uid, $create_group_id) {
		$fill = array(
			'founder_uid' => $founder_uid,
			'create_group_id' => $create_group_id,
		);
		return $this->fill($fill)->save();
	}

	public function getallGroupsByFounderUid($founder_uid) {
		return $this->getQuery()
			->select('*')
			->from('users_founder_own_create_groups', 'o')
			->innerjoin('users_create_group', 'c')
			->on(array('o.create_group_id' => 'c.id'))
			->where('o.founder_uid', $founder_uid)
			->getall();
	}

	public function getGroupsByFounderUid($founder_uid, $pageindex, $pagesize = 15) {
		$groups = $this->getQuery()
			->select('*')
			->from('users_founder_own_create_groups', 'o')
			->leftjoin('users_create_group', 'c')
			->on(array('o.create_group_id' => 'c.id'))
			->where('o.founder_uid', $founder_uid)
			->page($pageindex, $pagesize)
			->getall();
		$total = $this->getLastQueryTotal();
		return array('groups' => $groups, 'total' => $total);
	}

}