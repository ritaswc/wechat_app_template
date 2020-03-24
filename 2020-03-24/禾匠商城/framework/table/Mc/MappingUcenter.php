<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class MappingUcenter extends \We7Table {
	protected $tableName = 'mc_mapping_ucenter';
	protected $primaryKey = 'fanid';
	protected $field = array(
		'uniacid',
		'uid',
		'centeruid'
	);
	protected $default = array(
		'uniacid' => '',
		'uid' => '',
		'centeruid' => ''
	);
}