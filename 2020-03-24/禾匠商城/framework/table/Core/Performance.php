<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Core;

class Performance extends \We7Table {
	protected $tableName = 'core_performance';
	protected $primaryKey = 'id';
	protected $field = array(
		'type',
		'runtime',
		'runurl',
		'runsql',
		'createtime',
	);
	protected $default = array(
		'type' => '',
		'runtime' => '',
		'runurl' => '',
		'runsql' => '',
		'createtime' => '',
	);
}