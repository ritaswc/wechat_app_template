<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Modules;

class Ignore extends \We7Table {
	protected $tableName = 'modules_ignore';
	protected $primaryKey = 'id';
	protected $field = array(
		'mid',
		'name',
		'version',
	);
	protected $default = array(
		'mid' => '',
		'name' => '',
		'version' => '',
	);
	
	public function add($modulename, $version) {
		pdo_delete($this->tableName, array('name' => $modulename));
		$ignore_module = array(
			'name' => $modulename,
			'version' => $version
		);
		return pdo_insert($this->tableName, $ignore_module);
	}
}