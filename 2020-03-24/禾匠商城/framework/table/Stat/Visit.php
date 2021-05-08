<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Stat;

class Visit extends \We7Table {
	protected $tableName = 'stat_visit';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'type',
		'module',
		'count',
		'date',
	);
	protected $default = array(
		'uniacid' => '',
		'type' => '',
		'module' => '',
		'count' => '',
		'date' => '',
	);

	public function searchWithUnacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}

	public function searchWithDate($date) {
		return $this->query->where('date', $date);
	}

	public function searchWithGreaterThenDate($date) {
		return $this->query->where('date >=', $date);
	}

	public function searchWithLessThenDate($date) {
		return $this->query->where('date <=', $date);
	}

	public function searchWithModule($module) {
		return $this->query->where('module', $module);
	}

	public function searchWithType($type) {
		return $this->query->where('type', $type);
	}
}