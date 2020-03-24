<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class StatisticsTable extends We7Table {

	protected $stat_fans_table ='stat_fans';
	protected $stat_visit_table = 'stat_visit';

	public function visitList($params, $type = 'more') {
		global $_W;
		$this->query->from($this->stat_visit_table);
		if (!empty($params['uniacid'])) {
			$this->query->where('uniacid', $params['uniacid']);
		}
		if (!empty($params['date'])) {
			$this->query->where('date', $params['date']);
		}
		if (!empty($params['date >='])) {
			$this->query->where('date >=', $params['date >=']);
		}
		if (!empty($params['date <='])) {
			$this->query->where('date <=', $params['date <=']);
		}
		if (!empty($params['module'])) {
			$this->query->where('module', $params['module']);
		}
		if (!empty($params['type'])) {
			$this->query->where('type', $params['type']);
		}

		if ($type == 'one') {
			return $this->query->get();
		} else {
			return $this->query->getall();
		}
	}
}