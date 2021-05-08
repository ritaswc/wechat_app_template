<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Modules;

class Bindings extends \We7Table {
	protected $tableName = 'modules_bindings';
	protected $primaryKey = 'eid';
	protected $field = array(
		'module',
		'entry',
		'call',
		'title',
		'do',
		'state',
		'direct',
		'url',
		'icon',
		'displayorder',
		'multilevel',
		'parent',
	);
	protected $default = array(
		'module' => '',
		'entry' => '',
		'call' => '',
		'title' => '',
		'do' => '',
		'state' => '',
		'direct' => 0,
		'url' => '',
		'icon' => 'wi wi-appsetting',
		'displayorder' => 0,
		'multilevel' => '',
		'parent' => '',
	);

	public function searchWithModuleEntry($modulename, $entry) {
		return $this->query->where('module', $modulename)->where('entry', $entry);
	}

	public function deleteByName($modulename) {
		return $this->query->where('module', $modulename)->delete();
	}
	
	public function isEntryExists($modulename, $entry, $do) {
		return $this->query->where('module', $modulename)->where('entry', $entry)->where('do', $do)->exists();
	}
	
	public function isCallExists($modulename, $entry, $call) {
		return $this->query->where('module', $modulename)->where('entry', $entry)->where('call', $call)->exists();
	}

	public function getByEntryDo($modulename, $entry, $do) {
		return $this->query->where('module', $modulename)->where('entry', $entry)->where('do', $do)->get();
	}

	public function getByEntryCall($modulename, $entry, $call) {
		return $this->query->where('module', $modulename)->where('entry', $entry)->where('call', $call)->get();
	}
}