<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Core;

class Settings extends \We7Table {
	protected $tableName = 'core_settings';
	protected $primaryKey = 'key';
	protected $field = array(
		'value',
	);
	protected $default = array(
		'value' => '',
	);
}