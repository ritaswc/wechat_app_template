<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class Lastuse extends \We7Table {
	protected $tableName = 'users_lastuse';
	protected $primaryKey = 'id';
	protected $field = array(
		'uid',
		'uniacid',
		'modulename',
		'type',
	);
	protected $default = array(
		'uid' => '',
		'uniacid' => '0',
		'modulename' => '',
		'type' => '',
	);

	public function getByType($type) {
		global $_W;
		$this->searchWithUid($_W['uid']);
		return $this->query->where('type', $type)->get();
	}

	public function searchWithoutType($type) {
		return $this->query->where('type <>', $type);
	}

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}

	public function searchWithUid($uid) {
		return $this->query->where('uid', $uid);
	}

	public function searchWithModule($module) {
		return $this->query->where('modulename', $module);
	}

	public function getDefaultModulesAccount() {
		global $_W;
		return $this->query
			->select('m.uniacid as default_uniacid,m.modulename,m.uid,a.name as default_account_name')
			->from($this->tableName, 'm')
			->leftjoin('uni_account', 'a')
			->on(array('m.uniacid' => 'a.uniacid'))
			->where('m.uid', $_W['uid'])
			->getall('modulename');
	}
}