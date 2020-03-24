<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class ExtraModules extends \We7Table {
	protected $tableName = 'users_extra_modules';
	protected $primaryKey = 'id';
	protected $field = array(
		'uid',
		'module_name',
		'support',

	);
	protected $default = array(
		'uid' => '',
		'module_name' => '',
		'support' => '',

	);

	public function addExtraModule($uid, $module_name, $support) {
		$data = array(
			'uid' => $uid,
			'module_name' => $module_name,
			'support' => $support,
		);
		return $this->fill($data)->save();
	}

	public function searchBySupport($support) {
		return $this->query->where('support', $support);
	}

	public function searchByUid($uid) {
		return $this->query->where('uid', $uid);
	}

	public function searchByModuleName($module_name) {
		return $this->query->where('module_name', $module_name);
	}

	public function getExtraModuleByUidAndModulename($uid, $module_name) {
		$where = array(
			'uid' => $uid,
			'module_name' => $module_name,
		);
		return $extra_module = $this->where($where)->get();
	}

	public function getExtraModulesByUid($uid) {
		return $this->where(array('uid' => $uid))->getall();
	}

	public function deleteExtraModulesByUid($uid) {
		return $this->where(array('uid' => $uid))->delete();
	}


}