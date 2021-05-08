<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\System;

class StatVisit extends \We7Table {
	protected $tableName = 'system_stat_visit';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'modulename',
		'uid',
		'displayorder',
		'createtime',
		'updatetime',
	);
	protected $default = array(
		'uniacid' => '',
		'modulename' => '',
		'uid' => '',
		'displayorder' => 0,
		'createtime' => '',
		'updatetime' => '',
	);

	public function deleteVisitRecord($uid, $delete_modules = array()) {
		if (!empty($delete_modules)) {
			$this->query->where('modulename', $delete_modules);
		}
		return $this->query->where('uid', $uid)
			->where('uniacid', 0)
			->delete();
	}

	public function getVistedModule($uid) {
		return $this->query->where('uid', $uid)->where('uniacid', 0)->getall('modulename');
	}

	public function getVisitedModuleById($id) {
		return $this->query->where('id', $id)->get();
	}

	public function updateVisitedModuleUniacid($id, $uniacid) {
		return $this->where('id', $id)->fill('uniacid', $uniacid)->save();
	}
}