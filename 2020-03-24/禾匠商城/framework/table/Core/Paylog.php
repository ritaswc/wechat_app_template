<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
namespace We7\Table\Core;

class Paylog extends \We7Table {
	protected $tableName = 'core_paylog';
	protected $primaryKey = 'id';
	protected $field = array(
		'type',
		'uniacid',
		'acid',
		'openid',
		'uniontid',
		'tid',
		'fee',
		'status',
		'module',
		'tag',
		'is_usecard',
		'card_type',
		'card_id',
		'card_fee',
		'encrypt_code',
		'is_wish'
	);
	protected $default = array(
		'type' => '',
		'uniacid' => 0,
		'acid' => 0,
		'openid' => '',
		'uniontid' => '',
		'tid' => '',
		'fee' => 0,
		'status' => '',
		'module' => '',
		'tag' => '',
		'is_usecard' => 0,
		'card_type' => '',
		'card_id' => '',
		'card_fee' => '',
		'encrypt_code' => '',
		'is_wish' => 0
	);

	public function searchWithUniacid($uniacid)
	{
		return $this->query->where('uniacid', $uniacid);
	}

	public function searchWithModule($module)
	{
		return $this->query->where('module', $module);
	}

	public function searchWithTid($tid)
	{
		return $this->query->where('tid', $tid);
	}
}