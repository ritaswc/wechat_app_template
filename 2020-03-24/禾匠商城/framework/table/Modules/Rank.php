<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Modules;

class Rank extends \We7Table {
	protected $tableName = 'modules_rank';
	protected $primaryKey = 'id';
	protected $field = array(
		'module_name',
		'uid',
		'rank',
	);
	protected $default = array(
		'module_name' => '',
		'uid' => '',
		'rank' => '',
	);
	
	public function getByModuleNameList($modulename_list) {
		global $_W;
		$this->query->where('uid', $_W['uid']);
		if (!empty($modulename_list)) {
			$this->query->where('module_name', $modulename_list);
		}
		return $this->query->getall('module_name');
	}

	public function getAllByUid($uid) {
		global $_W;
		return $this->query
			->select(array('module_name', 'rank'))
			->where('uid', $_W['uid'])
			->getall('module_name');
	}

	public function getByModuleName($module_name) {
		global $_W;
		return $this->query->where('uid', $_W['uid'])->where('module_name', $module_name)->get();
	}

	public function getByModuleNameAndUniacid($module_name, $uniacid) {
		global $_W;
		return $this->query->where('uid', $_W['uid'])->where(array('module_name' => $module_name, 'uniacid' => $uniacid))->get();
	}
	
	public function getMaxRank() {
		global $_W;
		$rank_info = $this->query->select('max(rank)')->where('uid', $_W['uid'])->getcolumn();
		return $rank_info;
	}
	
	public function setTop($module_name, $uniacid) {
		global $_W;
		if (empty($module_name) || empty($uniacid)) {
			return false;
		}
		$max_rank = $this->getMaxRank();
		$exist = $this->getByModuleNameAndUniacid($module_name, $uniacid);
		if (!empty($exist)) {
			pdo_update($this->tableName, array('rank' => ($max_rank + 1)), array('module_name' => $module_name, 'uid' => $_W['uid'], 'uniacid' => $uniacid));
		} else {
			pdo_insert($this->tableName, array('uid' => $_W['uid'], 'module_name' => $module_name, 'uniacid' => $uniacid, 'rank' => ($max_rank + 1)));
		}
		return true;
	}

	public function getModuleListByUidAndUniacid() {
		global $_W;
		return $this->query->where(array('uid' => $_W['uid'], 'uniacid' => $_W['uniacid']))->getall('module_name');
	}

}