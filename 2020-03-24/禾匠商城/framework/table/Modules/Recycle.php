<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Modules;

class Recycle extends \We7Table {
	protected $tableName = 'modules_recycle';
	protected $primaryKey = 'id';
	protected $field = array(
		'name',
		'type',
		'account_support',
		'wxapp_support',
		'welcome_support',
		'webapp_support',
		'phoneapp_support',
		'xzapp_support',
		'aliapp_support',
		'baiduapp_support',
		'toutiaoapp_support',
	);
	protected $default = array(
		'name' => '',
		'type' => 0,
		'account_support' => 0,
		'wxapp_support' => 0,
		'welcome_support' => 0,
		'webapp_support' => 0,
		'phoneapp_support' => 0,
		'xzapp_support' => 0,
		'aliapp_support' => 0,
		'baiduapp_support' => 0,
		'toutiaoapp_support' => 0,
	);

	public function getByName($modulename, $key = 'type') {
		return $this->query->where('name', $modulename)->getall($key);
	}

	public function deleteByName($modulename) {
		return $this->query->where('name', $modulename)->delete();
	}

	public function searchWithNameType($name, $type) {
		$this->query->where('name', $name)->where('type', $type);
		return $this;
	}

	public function searchWithSupport($support) {
		$this->query->where($support, 1);
		return $this;
	}

	public function searchWithModulesCloud($fields = 'a.*') {
		return $this->query->from('modules_cloud', 'a')->select($fields)->leftjoin('modules_recycle', 'b')->on(array('a.name' => 'b.name'));

	}

	public function searchWithModules($fields = 'a.*') {
		return $this->query->from('modules', 'a')->select($fields)->leftjoin('modules_recycle', 'b')->on(array('a.name' => 'b.name'));

	}
}