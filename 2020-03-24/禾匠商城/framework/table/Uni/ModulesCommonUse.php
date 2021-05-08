<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class ModulesCommonUse extends \We7Table {
	protected $tableName = 'uni_modules_common_use';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'modulename',
		'uid',
		'displayorder',
		'createtime',
		'updatetime',

	);
	protected $default = array(
		'uniacid' => '',
		'modulename' => '',
		'uid' => '',
		'displayorder' => '0',
		'createtime' => '',
		'updatetime' => '',

	);

	public function searchByUid($uid) {
		return $this->query->where('uid', $uid);
	}

	public function searchByUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}

	public function searchByModuleName($modulename) {
		return $this->query->where('modulename', $modulename);
	}

	public function searchWithoutModuleName() {
		return $this->query->where('modulename', '');
	}

	public function getCommonUseModule($uid, $uniacid, $modulename) {
		return $this->where(array('uid' => $uid, 'uniacid' => $uniacid, 'modulename' => $modulename))->get();
	}

	public function getCommonUseModuleList($pageindex = 1, $pagesize = 15) {
		return $this->query->page($pageindex, $pagesize)->orderby('displayorder', 'desc')->getall();
	}
}