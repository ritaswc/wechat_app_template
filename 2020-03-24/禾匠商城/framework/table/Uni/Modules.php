<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class Modules extends \We7Table {
	protected $tableName = 'uni_modules';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'module_name',

	);
	protected $default = array(
		'uniacid' => '',
		'module_name' => '',

	);

	public function getallByUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid)->getall();
	}

	public function searchLikeModuleTitle($module_title) {
		return $this->query->where('m.title LIKE', "%{$module_title}%");
	}

	public function searchWithModuleLetter($module_letter) {
		return $this->query->where('m.title_initial', $module_letter);
	}

	public function searchWithModuleName($module_name) {
		$this->query->where('u.module_name', $module_name);
	}

	public function searchGroupbyModuleName() {
		return $this->query->groupby('u.module_name');
	}

	public function getModulesByUid($uid, $uniacid = 0) {
		global $_W;
		if (empty($uid)) {
			$uid = $_W['uid'];
		}

		if (!empty($uniacid)) {
			$this->where('u.uniacid', $uniacid);
		} else {
			$this->where('u.uniacid <>', 0);
		}

		$select_fields = "
			u.uniacid,
			u.module_name
		";

		if (!user_is_founder($uid) && $_W['highest_role'] != ACCOUNT_MANAGE_NAME_CLERK || user_is_vice_founder($uid)) {
			$select_fields .= ", uau.role";
			$this->where('uau.uid', $uid);
			$this->query->from('uni_account_users', 'uau')
				->leftjoin('uni_modules', 'u')
				->on(array('uau.uniacid' => 'u.uniacid'));
		} elseif ($_W['highest_role'] == ACCOUNT_MANAGE_NAME_CLERK) {
			$select_fields .= ", up.uniacid as permission_uniacid";
			$this->where('up.uid', $uid);
			$this->query->from('users_permission', 'up')
				->leftjoin('uni_modules', 'u')
				->on(array('up.type' => 'u.module_name', 'up.uniacid' => 'u.uniacid'));
		} else {
			$this->query->from('uni_modules', 'u');
		}

		$modules = $this->query
			->select($select_fields)
			->getall();

		$total = $this->getLastQueryTotal();
		return array('modules' => $modules, 'total' => $total);
	}

	public function deleteUniModules($module_name, $uniacid) {
		$this->query->where('module_name', $module_name)->where('uniacid', $uniacid)->delete();
	}

}