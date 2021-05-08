<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Stat;

class VisitIp extends \We7Table {
	protected $tableName = 'stat_visit_ip';
	protected $primaryKey = 'id';
	protected $field = array(
		'ip',
		'uniacid',
		'type',
		'module',
		'date',

	);
	protected $default = array(
		'ip' => '',
		'uniacid' => '',
		'type' => '',
		'module' => '',
		'date' => '',

	);

	public function searchWithIp($ip) {
		return $this->query->where('ip', $ip);
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