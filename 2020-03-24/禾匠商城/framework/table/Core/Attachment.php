<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Core;

class Attachment extends \We7Table {
	protected $tableName = 'core_attachment';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'uid',
		'filename',
		'attachment',
		'type',
		'createtime',
		'module_upload_dir',
		'group_id',
		'displayorder',
	);
	protected $default = array(
		'uniacid' => '',
		'uid' => '',
		'filename' => '',
		'attachment' => '',
		'type' => '',
		'createtime' => '',
		'module_upload_dir' => '',
		'group_id' => '0',
		'displayorder' => '',
	);

	public function deleteById($id) {
		return $this->where('id', $id)->delete();
	}

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}

	public function searchWithUid($uid) {
		return $this->query->where('uid', $uid);
	}

	public function searchWithUploadDir($module_upload_dir) {
		return $this->query->where(array('module_upload_dir' => $module_upload_dir));
	}

	public function searchWithType($type) {
		return $this->query->where(array('type' => $type));
	}

	public function searchWithGroupId($groupid) {
		return $this->query->where(array('group_id =' => $groupid));
	}

	public function searchWithTime($start_time, $end_time) {
		return $this->query->where(array('createtime >=' => $start_time))->where(array('createtime <=' => $end_time));
	}

	public function SearchWithUserAndUniAccount() {
		return $this->query->from($this->tableName, 'a')
			->leftjoin('users', 'b')
			->on('b.uid', 'a.uid')
			->leftjoin('uni_account', 'c')
			->on('a.uniacid','c.uniacid');
	}
}