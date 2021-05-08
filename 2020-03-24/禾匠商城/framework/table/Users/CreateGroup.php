<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class CreateGroup extends \We7Table {
	protected $tableName = 'users_create_group';
	protected $primaryKey = 'id';
	protected $field = array(
		'group_name',
		'maxaccount',
		'maxwxapp',
		'maxwebapp',
		'maxphoneapp',
		'maxwzapp',
		'maxaliapp',
		'maxbaiduapp',
		'maxtoutiaoapp',
		'createtime',

	);
	protected $default = array(
		'group_name' => '',
		'maxaccount' => '0',
		'maxwxapp' => '0',
		'maxwebapp' => '0',
		'maxphoneapp' => '0',
		'maxwzapp' => '0',
		'maxaliapp' => '0',
		'maxbaiduapp' => '0',
		'maxtoutiaoapp' => '0',
		'createtime' => '',

	);

	public function searchWithGroupName($group_name) {
		$this->where('group_name', $group_name);
		return $this;
	}

	public function searchLikeGroupName($group_name) {
		$this->where("group_name LIKE", "%{$group_name}%");
		return $this;
	}

	public function searchWithId($id) {
		$this->where('id', $id);
		return $this;
	}

	public function searchWithoutId($id) {
		$this->where('id !=', $id);
		return $this;
	}

	public function getCreateGroupInfo() {
		return $this->get();
	}

	public function getCreateGroupInfoById($id) {
		return $this->where('id', $id)->get();
	}

	public function getCreateGroupList() {
		return $this->getall();
	}

	public function deleteById($id) {
		return $this->where('id', $id)->delete();
	}

}