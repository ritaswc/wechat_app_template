<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class CreditsRecord extends \We7Table {
	protected $tableName = 'mc_credits_record';
	protected $primaryKey = 'id';
	protected $field = array(
		'clerk_id',
		'clerk_type',
		'createtime',
		'credittype',
		'module',
		'num',
		'operator',
		'real_uniacid',
		'remark',
		'store_id',
		'uid',
		'uniacid',
	);
	protected $default = array(
		'clerk_id' => '0',
		'clerk_type' => '1',
		'createtime' => '',
		'credittype' => '',
		'module' => '',
		'num' => '0.00',
		'operator' => '',
		'real_uniacid' => '',
		'remark' => '',
		'store_id' => '0',
		'uid' => '',
		'uniacid' => '',
	);

	public function getCreditsRecordListByUidAndCredittype($uid, $credittype) {
		return $this->query->from('mc_credits_record', 'r')
					->select('r.*, u.username as username')
					->leftjoin('users', 'u')
					->on(array('r.operator' => 'u.uid'))
					->where('r.uid', $uid)
					->where('r.credittype', $credittype)
					->orderby('r.id', 'desc')
					->getall();
	}

	public function searchWithUsers() {
		return $this->query->from($this->tableName, 'r')
			->leftjoin('users', 'u')
			->on(array('r.operator' => 'u.uid'));
	}

	public function searchWithUniacid($uniacid) {
		return $this->query->where('uniacid', $uniacid);
	}
}