<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Core;

class Cron extends \We7Table {
	protected $tableName = 'core_cron';
	protected $primaryKey = 'id';
	protected $field = array(
		'cloudid',
		'module',
		'uniacid',
		'type',
		'name',
		'filename',
		'lastruntime',
		'nextruntime',
		'weekday',
		'day',
		'hour',
		'minute',
		'extra',
		'status',
		'createtime'
	);
	protected $default = array(
		'cloudid' => 0,
		'module' => '',
		'uniacid' => 0,
		'type' => 1,
		'name' => '',
		'filename' => '',
		'lastruntime' => 0,
		'nextruntime' => 0,
		'weekday' => -1,
		'day' => -1,
		'hour' => -1,
		'minute' => '',
		'extra' => '',
		'status' => 0,
		'createtime' => 0
	);

}