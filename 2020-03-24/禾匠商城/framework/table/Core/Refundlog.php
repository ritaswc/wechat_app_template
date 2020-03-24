<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Core;

class Refundlog extends \We7Table {
	protected $tableName = 'core_refundlog';
	protected $primaryKey = 'id';
	protected $field = array(
		'pid',
		'title',
		'group_name',
		'name',
		'icon',
		'url',
		'append_title',
		'append_url',
		'displayorder',
		'type',
		'is_display',
		'is_system',
		'permission_name',
	);
	protected $default = array(
		'pid' => '0',
		'title' => '',
		'group_name' => '',
		'name' => '',
		'icon' => '',
		'url' => '',
		'append_title' => '',
		'append_url' => '',
		'displayorder' => '0',
		'type' => 'url',
		'is_display' => '1',
		'is_system' => '0',
		'permission_name' => '',
	);

	public function getByUniontid($uniontid) {
		return $this->query->where('uniontid', $uniontid)->get();
	}

}