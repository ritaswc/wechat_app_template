<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
class AttachmentgroupTable extends We7Table {

	protected $tableName = 'attachment_group';
	protected $field = array('uid', 'uniacid', 'name', 'type');


	public function searchWithUniacidOrUid($uniacid, $uid) {
		if (empty($uniacid)) {
			$this->query->where('uid', $uid);
		} else {
			$this->query->where('uniacid', $uniacid);
		}
		return $this;
	}

	
	public function deleteByUniacid($uniacid) {
		return $this->where('uniacid', $uniacid)->delete();
	}
}