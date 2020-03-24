<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Modules;

class Plugin extends \We7Table {
	protected $tableName = 'modules_plugin';
	protected $primaryKey = 'id';
	protected $field = array(
		'name',
		'main_module',
	);
	protected $default = array(
		'name' => '',
		'main_module' => '',
	);

	public function getAllByNameOrMainModule($modulenams) {
		return $this->where('name', $modulenams)->whereor('main_module', $modulenams)->getall();
	}

	public function deleteByMainModule($modulename) {
		if (empty($modulename)) {
			return true;
		}
		return $this->query->where('main_module', $modulename)->delete();
	}
	
	public function getPluginExists($main_module, $plugin_name) {
		return $this->query->where('main_module', $main_module)->where('name', $plugin_name)->exists();
	}
}