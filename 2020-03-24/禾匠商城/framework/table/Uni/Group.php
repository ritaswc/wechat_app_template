<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class Group extends \We7Table {
	protected $tableName = 'uni_group';
	protected $primaryKey = 'id';
	protected $field = array(
		'owner_uid',
		'name',
		'uniacid',
		'modules',
		'templates',
		'uid',

	);
	protected $default = array(
		'owner_uid' => '0',
		'name' => '',
		'uniacid' => '0',
		'modules' => '',
		'templates' => '',
		'uid' => '0',

	);

	public function getData($key) {
		$data = $this->getall($key);
		if (!empty($data)) {
			foreach ($data as &$row) {
				if (!empty($row['modules'])) {
					$row['modules'] = iunserializer($row['modules']);
				}
				if (!empty($row['templates'])) {
					$row['templates'] = iunserializer($row['templates']);
				}
			}
		}
		return $data;
	}

	public function searchWithUniacidAndUid($uniacid = 0, $uid = 0) {
		return $this->where('u.uniacid', $uniacid)->where('u.uid', $uid);
	}

	public function searchWithName($name) {
		return $this->query->where('u.name LIKE', "%{$name}%");
	}

	public function searchWithFounderUid($founder_uid) {
		return $this->query
			->select('u.*, f.founder_uid, f.uni_group_id')
			->leftjoin('users_founder_own_uni_groups', 'f')
			->on(array('u.id' => 'f.uni_group_id'))
			->where('f.founder_uid', $founder_uid);
	}

	public function getUniGroupList() {
		return $this->query
			->from('uni_group', 'u')
			->getall('u.id');
	}
}