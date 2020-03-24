<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Rule;

class Rule extends \We7Table {
	protected $tableName = 'rule';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'name',
		'module',
		'containtype',
		'displayorder',
		'status',
	);
	protected $default = array(
		'uniacid' => '0',
		'name' => '',
		'module' => '',
		'containtype' => '',
		'displayorder' => '0',
		'status' => '1',
	);
}