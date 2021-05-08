<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class FounderGroup extends \We7Table {
	protected $tableName = 'users_founder_group';
	protected $primaryKey = 'id';
	protected $field = array(
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
		'name' => '',
		'package' => '',
		'maxaccount' => '0',
		'maxsubaccount' => '',
		'timelimit' => '0',
		'maxwxapp' => '0',
		'maxwebapp' => '0',
		'maxphoneapp' => '0',
		'maxxzapp' => '0',
		'maxaliapp' => '0',
		'maxbaiduapp' => '0',
		'maxtoutiaoapp' => '0',
	);

	public function searchWithName($name) {
		return $this->where('name', $name);
	}

	public function searchWithNoId($id) {
		return $this->where('id !=', $id);
	}

	public function getUserFounderGroupById($id) {
		return $this->where('id', $id)->get();
	}

	public function getUserFounderGroupList() {
		return $this->query->from('users_founder_group')->getall('id');
	}

}