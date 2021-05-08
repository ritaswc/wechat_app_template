<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Store;

class CreateAccount extends \We7Table {
	protected $tableName = 'site_store_create_account';
	protected $primaryKey = 'id';
	protected $field = array(
		'uid',
		'uniacid',
		'type',
		'endtime',
	);
	protected $default = array(
		'uid' => '',
		'uniacid' => '',
		'type' => '',
		'endtime' => '',
	);

	public function getByUniacid($uniacid) {
		return $this->where('uniacid', $uniacid)->get();
	}

	public function getQueryJoinAccountTable($uid = 0, $type = 0) {
		$query = $this->query
			->from($this->tableName, 'a')
			->leftjoin('account', 'b')
			->on('a.uniacid', 'b.uniacid');
		if ($uid > 0) {
			$query->where('a.uid', intval($uid));
		}
		if ($type > 0) {
			$query->where('a.type', intval($type));
		}
		return $query;
	}

	public function getUserCreateAccountNum($uid) {
		return $this->getQueryJoinAccountTable($uid, ACCOUNT_TYPE_OFFCIAL_NORMAL)->getcolumn('count(*)');
	}

	public function getUserCreateWxappNum($uid) {
		return $this->getQueryJoinAccountTable($uid, ACCOUNT_TYPE_APP_NORMAL)->getcolumn('count(*)');
	}

	public function getUserDeleteNum($uid, $type) {
		$sql = "SELECT COUNT(*) FROM "
			. tablename($this->tableName)
			. " as a LEFT JOIN " . tablename('account')
			. " as b ON a.uniacid = b.uniacid WHERE a.uid = :uid AND a.type = :type AND (b.isdeleted = 1 OR b.uniacid is NULL)";
		return pdo_fetchcolumn($sql, array(':uid' => $uid, ':type' => $type));
	}
}