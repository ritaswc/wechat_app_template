<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class Group extends \We7Table {
	protected $tableName = 'users_group';
	protected $primaryKey = 'id';
	protected $field = array(
		'owner_uid',
		'name',
		'package',
		'maxaccount',
		'maxsubaccount',
		'timelimit',
		'maxwxapp',
		'maxwebapp',
		'maxphoneapp',
		'maxxzapp',
		'maxaliapp',
		'maxbaiduapp',
		'maxtoutiaoapp',
	);
	protected $default = array(
		'owner_uid' => '0',
		'name' => '',
		'package' => '',
		'maxaccount' => '0',
		'maxsubaccount' => '',
		'timelimit' => '0',
		'maxwxapp' => '',
		'maxwebapp' => '0',
		'maxphoneapp' => '0',
		'maxxzapp' => '0',
		'maxaliapp' => '0',
		'maxbaiduapp' => '0',
		'maxtoutiaoapp' => '0',
	);

	public function getAllById($ids) {
		$data = $this->where('id', $ids)->getall('id');
		if (!empty($data)) {
			foreach ($data as &$item) {
				$item['package'] = iunserializer($item['package']);
			}
		}
		return $data;
	}

	public function searchWithNameLike($name) {
		return $this->where('u.name LIKE', "%{$name}%");
	}

	public function searchWithName($name) {
		return $this->where('u.name', $name);
	}

	public function searchWithNoId($id) {
		$this->query->where('u.id !=', $id);
		return $this;
	}

	public function getUsersGroupList() {
		return $this->query->from('users_group', 'u')
			->getall('u.id');
	}

	public function getOwnUsersGroupsList($founder_uid) {
		return $this->query
			->select('f.id as fid, u.*')
			->leftjoin('users_founder_own_users_groups', 'f')
			->on(array('u.id' => 'f.users_group_id'))
			->where('f.founder_uid', $founder_uid);
	}



}