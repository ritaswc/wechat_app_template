<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');

class GroupTable extends We7Table {
	protected $tableName = 'users_group';
	protected $founderGroupTableName = 'users_founder_group';

	public function groupList($is_vice_founder = false) {
		$table_name = empty($is_vice_founder) ? $this->tableName : $this->founderGroupTableName;
		return $this->query->from($table_name)->getall();
	}

	public function searchGroup($is_vice_founder = false) {
		$table_name = empty($is_vice_founder) ? $this->tableName : $this->founderGroupTableName;
		return $this->query->from($table_name)->get();
	}

	public function searchWithId($id) {
		$this->query->where('id', $id);
		return $this;
	}

	public function searchWithName($name) {
		$this->query->where('name', $name);
		return $this;
	}

	public function searchWithNoId($id) {
		$this->query->where('id !=', $id);
		return $this;
	}
}