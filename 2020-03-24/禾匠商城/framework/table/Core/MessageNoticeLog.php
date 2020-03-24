<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Core;

class MessageNoticeLog extends \We7Table {
	protected $tableName = 'message_notice_log';
	protected $primaryKey = 'id';
	protected $field = array(
		'message',
		'is_read',
		'uid',
		'sign',
		'type',
		'status',
		'create_time',
		'end_time',
		'url',
	);
	protected $default = array(
		'message' => '',
		'is_read' => '1',
		'uid' => '0',
		'sign' => '',
		'type' => '0',
		'status' => '0',
		'create_time' => '0',
		'end_time' => '0',
		'url' => '',
	);

	public function searchWithUid($uid) {
		return $this->query->where('uid', $uid);
	}

	public function searchWithIsRead($is_read) {
		return $this->query->where('is_read', $is_read);
	}

	public function searchWithType($type) {
		return $this->query->where('type', $type);
	}

	public function searchWithOutType($type) {
		return $this->query->where('type !=', $type);
	}
	public function messageExists($message) {
		return $this->query->where($message)->get();
	}
}