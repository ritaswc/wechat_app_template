<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class AccountModules extends \We7Table {
	protected $tableName = 'uni_account_modules';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'module',
		'enabled',
		'shortcut',
		'displayorder',
		'settings',
	);
	protected $default = array(
		'uniacid' => '',
		'module' => '',
		'enabled' => 0,
		'shortcut' => 0,
		'displayorder' => 0,
		'settings' => '',
	);

	public function isSettingExists($module_name) {
		global $_W;
		return $this->query->where('module', $module_name)->where('uniacid', $_W['uniacid'])->exists();
	}

	public function getByUniacidAndVersionId($uniacid, $version_id) {
		$data = $this->query->where('uniacid', $uniacid)->where('version_id', $version_id)->get();
		if (!empty($data['settings'])) {
			$data['settings'] = iunserializer($data['settings']);
		}
		return $data;
	}

	public function getByUniacidAndModule($module_name, $uniacid) {
		$result = $this->query->where('module', $module_name)->where('uniacid', $uniacid)->get();
		if (!empty($result)) {
			$result['settings'] = iunserializer($result['settings']);
		}
		return $result;
	}
}