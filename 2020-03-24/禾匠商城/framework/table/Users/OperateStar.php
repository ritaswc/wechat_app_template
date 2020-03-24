<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Users;

class OperateStar extends \We7Table {
	protected $tableName = 'users_operate_star';
	protected $primaryKey = 'id';
	protected $field = array(
		'createtime',
		'rank',
		'module_name',
		'type',
		'uid',
		'uniacid',
	);
	protected $default = array(
		'createtime' => '',
		'rank' => 0,
		'module_name' => '',
		'type' => '',
		'uid' => '',
		'uniacid' => '',
	);

	public function getMaxRank() {
		global $_W;
		$rank_info = $this->query->select('max(rank)')->where('uid', $_W['uid'])->getcolumn();
		return $rank_info;
	}
	public function getALlByUid($uid) {
		return $this->query->where('uid', $uid)->orderby('rank', 'DESC')->getall();
	}

	public function getByUidUniacidModulename($uid, $uniacid, $module_name) {
		return $this->query->where('uid', $uid)->where('uniacid', $uniacid)->where('module_name', $module_name)->get();
	}
	public function searchWithLimit($limit_num) {
		return $this->query->limit($limit_num);
	}
}