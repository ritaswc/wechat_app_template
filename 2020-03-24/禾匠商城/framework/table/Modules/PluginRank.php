<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Modules;

class PluginRank extends \We7Table {
	protected $tableName = 'modules_plugin_rank';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'uid',
		'rank',
		'plugin_name',
		'main_module_name',

	);
	protected $default = array(
		'uniacid' => '0',
		'uid' => '0',
		'rank' => '0',
		'plugin_name' => '',
		'main_module_name' => '',

	);

	public function getByPluginNameAndUniacid($plugin_name, $uniacid) {
		global $_W;
		return $this->query->where('uid', $_W['uid'])->where(array('plugin_name' => $plugin_name, 'uniacid' => $uniacid))->get();
	}

	public function getMaxRank() {
		global $_W;
		$rank_info = $this->query->select('max(rank)')->where('uid', $_W['uid'])->getcolumn();
		return $rank_info;
	}

	public function setTop($plugin_name, $main_module_name, $uniacid) {
		global $_W;
		if (empty($plugin_name) || empty($uniacid)) {
			return false;
		}
		$max_rank = $this->getMaxRank();
		$exist = $this->getByPluginNameAndUniacid($plugin_name, $uniacid);
		if (!empty($exist)) {
			pdo_update($this->tableName, array('rank' => ($max_rank + 1)), array('plugin_name' => $plugin_name, 'uid' => $_W['uid'], 'uniacid' => $uniacid));
		} else {
			pdo_insert($this->tableName, array('uid' => $_W['uid'], 'plugin_name' => $plugin_name, 'main_module_name' => $main_module_name, 'uniacid' => $uniacid, 'rank' => ($max_rank + 1)));
		}
		return true;
	}

	public function searchWithUid($uid) {
		return $this->query->where('r.uid', $uid);
	}

	public function getPluginRankList($main_module, $uniacid) {
		return $this->query
			->select('p.name, r.uid, r.uniacid, r.rank')
			->from('modules_plugin', 'p')
			->leftjoin('modules_plugin_rank', 'r')
			->on(array('p.name' => 'r.plugin_name'))
			->where('p.main_module', $main_module)
			->where('r.uniacid', $uniacid)
			->orderby('r.rank', 'DESC')
			->getall('name');
	}
}