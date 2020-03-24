<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class AccountModulesShortcut extends \We7Table {
	protected $tableName = 'uni_account_modules_shortcut';
	protected $primaryKey = 'id';
	protected $field = array(
		'title',
		'url',
		'icon',
		'uniacid',
		'version_id',
		'module_name',

	);
	protected $default = array(
		'title' => '',
		'url' => '',
		'icon' => '',
		'uniacid' => '0',
		'version_id' => '0',
		'module_name' => '',

	);

	public function saveShortcut($fill, $id = 0) {
		if (!empty($id)) {
			$this->where('id', $id);
		}
		return $this->fill($fill)->save();
	}

	public function getShortcutListByUniacidAndModule($uniacid, $module, $pageindex = 1, $pagesize = 15) {
		$list = $this->query->where(array('uniacid' => $uniacid, 'module_name' => $module))->page($pageindex, $pagesize)->getall();
		$total = $this->getLastQueryTotal();
		return array('lists' => $list,  'total' => $total);
	}

	public function getShortcutById($id) {
		return $this->where('id', $id)->get();
	}

	public function deleteShortcutById($id) {
		return $this->where('id', $id)->delete();
	}
}