<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Mc;

class CardRecord extends \We7Table {
	protected $tableName = 'mc_card_record';
	protected $primaryKey = 'id';
	protected $field = array(
		'uniacid',
		'uid',
		'type',
		'model',
		'fee',
		'tag',
		'note',
		'remark',
		'addtime',
	);
	protected $default = array(
		'uniacid' => 0,
		'uid' => 0,
		'type' => '',
		'model' => 1,
		'fee' => 0,
		'tag' => '',
		'note' => '',
		'remark' => '',
		'addtime' => 0,
	);

	public function getByUid($uid, $uniacid) {
		return $this->query->where('uniacid', $uniacid)->where('uid', $uid)->get();
	}
}