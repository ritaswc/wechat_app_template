<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Uni;

class AccountMenus extends \We7Table {
	protected $tableName = 'uni_account_menus';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'menuid',
		'type',
		'title',
		'sex',
		'group_id',
		'client_platform_type',
		'area',
		'data',
		'status',
		'createtime',
		'isdeleted',
	);
	protected $default = array(
		'uniacid' => '0',
		'menuid' => '0',
		'type' => '1',
		'title' => '',
		'sex' => '0',
		'group_id' => '-1',
		'client_platform_type' => '0',
		'area' => '',
		'data' => '',
		'status' => '0',
		'createtime' => '0',
		'isdeleted' => '0',
	);

	public function searchWithTypeAndUniacid($type = '', $uniacid = 0) {
		if (!empty($type)) {
			$this->where('type', $type);
		}
		if ($uniacid > 0) {
			$this->where('uniacid', intval($uniacid));
		}
		return $this;
	}

	public function getByType($type = '') {
		global $_W;
		return $this->searchWithTypeAndUniacid($type, $_W['uniacid'])->get();
	}

	public function getAllByType($type = '') {
		global $_W;
		return $this->searchWithTypeAndUniacid($type, $_W['uniacid'])->getall('id');
	}
}