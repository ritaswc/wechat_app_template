<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class CardMembers extends \We7Table {
	protected $tableName = 'mc_card_members';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'uid',
		'openid',
		'cid',
		'cardsn',
		'status',
		'createtime',
		'nums',
		'endtime',
	);
	protected $default = array(
		'uniacid' => '',
		'uid' => '',
		'openid' => '',
		'cid' => 0,
		'cardsn' => '',
		'status' => '',
		'createtime' => 0,
		'nums' => 0,
		'endtime' => 0,
	);

	public function getByUid($uid, $uniacid) {
		return $this->query->where('uniacid', $uniacid)->where('uid', $uid)->get();
	}
}