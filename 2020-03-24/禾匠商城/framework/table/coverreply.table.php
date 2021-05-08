<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
class CoverreplyTable extends We7Table {

	protected $tableName = 'cover_reply';
	protected $primaryKey = 'id';
	protected $field = array('id', 'uniacid', 'multiid', 'rid', 'module', 'do', 'type', 'title', 'description', 'thumb', 'url');
	public function getCoverReplayInfo($module, $uniacid) {
		$result = $this->query->from($this->tableName)->where('module', $module)->where('uniacid', $uniacid)->get();
		return $result;
	}

	public function searchWithMultiid($multiid) {
		return $this->query->where('multiid', $multiid);
	}
}