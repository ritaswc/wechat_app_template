<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class Groups extends \We7Table {
	protected $tableName = 'mc_groups';
	protected $primaryKey = 'groupid';
	protected $field = array(
		'uniacid',
		'title',
		'credit',
		'isdefault'
	);
	protected $default = array(
		'uniacid' => 0,
		'title' => '',
		'credit' => 0,
		'isdefault' => 0
	);
}